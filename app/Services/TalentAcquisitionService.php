<?php

namespace App\Services;

use Google_Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Exception;
use Illuminate\Support\Facades\Cache;

class TalentAcquisitionService
{
    private $service;

    public function __construct()
    {
        $client = new Google_Client();
        $authConfigPath = config('services.google.auth_json');
        if (!file_exists($authConfigPath)) {
            throw new Exception("Google service account JSON file not found at: {$authConfigPath}");
        }
        $client->setAuthConfig($authConfigPath);
        $client->addScope(Sheets::SPREADSHEETS);
        $this->service = new Sheets($client);
    }

    public function capitalizeName($name)
    {
        if (!$name || !is_string($name)) return $name;
        return ucwords(strtolower($name));
    }

    public function convertDriveUrl($url)
    {
        if (!$url) return "https://cdn.vectorstock.com/i/500p/08/19/gray-photo-placeholder-icon-design-ui-vector-35850819.jpg";
        preg_match('/[-\w]{25,}/', $url, $matches);
        return $matches ? "https://drive.google.com/thumbnail?id=" . $matches[0] : "https://cdn.vectorstock.com/i/500p/08/19/gray-photo-placeholder-icon-design-ui-vector-35850819.jpg";
    }

    public function getSpreadsheetIdFromUrl($url)
    {
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    public function getSheetNames($spreadsheetId)
    {
        return Cache::remember("sheets_list_{$spreadsheetId}", 3600, function () use ($spreadsheetId) {
            try {
                $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
                $sheets = $spreadsheet->getSheets();
                $sheetNames = [];
                foreach ($sheets as $sheet) {
                    $props = $sheet->getProperties();
                    if (!isset($props['hidden']) || !$props['hidden']) {
                        $sheetNames[] = $props->getTitle();
                    }
                }
                return $sheetNames;
            } catch (Exception $e) {
                error_log("Error fetching sheet names: " . $e->getMessage());
                return [];
            }
        });
    }

    public function getDynamicData($spreadsheetId, $sheetName = null)
    {
        try {
            if (!$sheetName) {
                $sheets = $this->getSheetNames($spreadsheetId);
                if (empty($sheets)) throw new Exception("No visible sheets found.");
                $sheetName = $sheets[0];
            }

            $response = $this->service->spreadsheets_values->get($spreadsheetId, $sheetName);
            $values = $response->getValues();
            
            if (empty($values) || count($values) < 2) return [];
            
            $staffData = [];
            // Skip header row
            foreach (array_slice($values, 1) as $row) {
                 if (empty($row) || count($row) < 5) continue;

                // Dynamic column mapping fallbacks based on typical Google Forms + standard HR templates
                $photoUrl = $row[51] ?? $row[44] ?? $row[96] ?? $row[42] ?? "";

                $staffData[] = [
                    'firstName' => $this->capitalizeName($row[12] ?? $row[13] ?? ""),
                    'lastName' => $this->capitalizeName($row[14] ?? $row[15] ?? ""),
                    'applyingAs' => $row[5] ?? $row[1] ?? "N/A",
                    'address' => $row[15] ?? $row[16] ?? "",
                    'gender' => $row[21] ?? $row[25] ?? "",
                    'age' => $row[20] ?? $row[24] ?? "",
                    'email' => $row[17] ?? $row[21] ?? "",
                    'contact' => $row[16] ?? $row[20] ?? "",
                    'pdfUrl' => $row[72] ?? $row[64] ?? $row[66] ?? $row[100] ?? $row[73] ?? "",
                    'docUrl' => $row[73] ?? $row[65] ?? $row[67] ?? $row[101] ?? $row[74] ?? "",
                    'editUrl' => $row[74] ?? $row[66] ?? $row[68] ?? $row[102] ?? $row[75] ?? "",
                    // Try to catch 'Status' in common columns
                    'status' => $row[75] ?? $row[62] ?? $row[71] ?? $row[64] ?? $row[103] ?? "Pending",
                    'photoUrl' => $this->convertDriveUrl($photoUrl),
                ];
            }
            return $staffData;
        } catch (Exception $e) {
            error_log("Dynamic Data Error: " . $e->getMessage());
            return [];
        }
    }

    public function updateCandidateStatus($spreadsheetId, $sheetName, $email, $status)
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $sheetName);
            $values = $response->getValues();
            if (empty($values)) throw new Exception("No data found in sheet.");

            // 1. Find the 'Email' column index (dynamically if possible, or use likely defaults)
            $header = $values[0];
            $emailColIndex = -1;
            $statusColIndex = -1;

            // Try to find columns by header name first
            foreach ($header as $index => $colName) {
                $colNameLower = strtolower(trim($colName));
                if (str_contains($colNameLower, 'email') && $emailColIndex === -1) {
                    $emailColIndex = $index;
                }
                if (str_contains($colNameLower, 'status') && $statusColIndex === -1) {
                    $statusColIndex = $index;
                }
            }

            // Fallbacks if headers aren't clear (based on your previous hardcoded values)
            if ($emailColIndex === -1) $emailColIndex = 17; // Default fallback
            if ($statusColIndex === -1) $statusColIndex = 75; // Default fallback (Column BX)

            // 2. Find the row with the matching email
            $rowIndex = null;
            foreach ($values as $index => $row) {
                // index + 1 because Sheets are 1-indexed
                if ($index > 0 && isset($row[$emailColIndex]) && trim($row[$emailColIndex]) === trim($email)) {
                    $rowIndex = $index + 1;
                    break;
                }
            }

            if (!$rowIndex) throw new Exception("Candidate email not found in this sheet.");

            // 3. Convert status column index to letter (e.g., 75 -> BX)
            $statusColLetter = $this->numberToCol($statusColIndex + 1); // +1 because function expects 1-based index

            $range = "'{$sheetName}'!{$statusColLetter}{$rowIndex}";
            $body = new ValueRange(['values' => [[$status]]]);
            $result = $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, ['valueInputOption' => 'RAW']);

            return ['success' => true, 'updatedCells' => $result->getUpdatedCells()];
        } catch (Exception $e) {
            error_log("Update Status Error: " . $e->getMessage());
            throw new Exception("Failed to update status: " . $e->getMessage());
        }
    }

    // Helper to convert 1-based column number to Excel-style letter (e.g., 1->A, 27->AA)
    private function numberToCol($num) {
        $letter = '';
        while ($num > 0) {
            $m = ($num - 1) % 26;
            $letter = chr(65 + $m) . $letter;
            $num = floor(($num - $m - 1) / 26);
        }
        return $letter;
    }
}