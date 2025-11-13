<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $driveService;
    protected $templateFolderId = '1ec0RweNjnNYRgmD6eRsgNpCEdmMzNKqA'; // Your Template Folder ID

    public function __construct()
    {
        try {
            $credentialsPath = base_path('stafify-5d43b0bf4f7b.json'); 
            if (!file_exists($credentialsPath)) {
                throw new Exception("Service account credentials file not found at: {$credentialsPath}");
            }

            $this->client = new Google_Client();
            $this->client->setAuthConfig($credentialsPath);
            $this->client->addScope(Google_Service_Drive::DRIVE);
            $this->driveService = new Google_Service_Drive($this->client);

        } catch (Exception $e) {
            logger()->error('Google Drive Service Initialization Failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Creates a new folder, copies contents from the template, and shares with the user.
     *
     * @param string $folderName The name for the new folder (e.g., user's company name)
     * @param string $userEmail The email address to share the folder with
     * @return array ['folderId', 'folderLink']
     * @throws Exception
     */
    public function duplicateFolderForUser(string $folderName, string $userEmail): array
    {
        // 1. Create the new parent folder
        $folderMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            // You can add 'parents' => ['YOUR_ROOT_FOLDER_ID'] if you want to organize all new folders
        ]);
        
        $newFolder = $this->driveService->files->create($folderMetadata, ['fields' => 'id, webViewLink']);
        $newFolderId = $newFolder->getId();
        $folderLink = $newFolder->getWebViewLink();

        // 2. Recursively copy contents from template folder to new folder
        $this->copyFolderContents($this->templateFolderId, $newFolderId);

        // 3. Share the new folder with the user
        $permission = new Google_Service_Drive_Permission([
            'type' => 'user',
            'role' => 'writer',
            'emailAddress' => $userEmail
        ]);

        $this->driveService->permissions->create($newFolderId, $permission, [
            'sendNotificationEmail' => true // Notifies the user
        ]);

        // 4. Share with the static admin email from .env
        $adminEmail = env('GOOGLE_DRIVE_ADMIN_EMAIL');

        if ($adminEmail) {
            try {
                $adminPermission = new Google_Service_Drive_Permission([
                    'type' => 'user',
                    'role' => 'writer', // You can change this to 'reader' if you only want viewing access
                    'emailAddress' => $adminEmail
                ]);

                $this->driveService->permissions->create($newFolderId, $adminPermission, [
                    'sendNotificationEmail' => false // Set to true if you want the admin to be notified
                ]);
            } catch (Exception $e) {
                // If this fails, just log it but don't stop the process.
                // The user approval was still a success.
                logger()->error("Failed to share folder {$newFolderId} with admin {$adminEmail}: " . $e->getMessage());
            }
        }
        

        return [
            'folderId' => $newFolderId,
            'folderLink' => $folderLink
        ];
    }

    /**
     * Recursively copies all files and folders from a source folder to a destination folder.
     *
     * @param string $sourceId
     * @param string $destinationId
     */
    private function copyFolderContents(string $sourceId, string $destinationId)
    {
        $pageToken = null;
        do {
            $response = $this->driveService->files->listFiles([
                'q' => "'{$sourceId}' in parents and trashed=false",
                'fields' => 'nextPageToken, files(id, name, mimeType)',
                'pageToken' => $pageToken
            ]);

            foreach ($response->getFiles() as $file) {
                if ($file->getMimeType() === 'application/vnd.google-apps.folder') {
                    // It's a subfolder, create it in the destination
                    $subFolderMetadata = new Google_Service_Drive_DriveFile([
                        'name' => $file->getName(),
                        'mimeType' => 'application/vnd.google-apps.folder',
                        'parents' => [$destinationId]
                    ]);
                    $newSubFolder = $this->driveService->files->create($subFolderMetadata, ['fields' => 'id']);
                    
                    // Recurse into the subfolder
                    $this->copyFolderContents($file->getId(), $newSubFolder->getId());

                } else {
                    // It's a file, copy it
                    $fileMetadata = new Google_Service_Drive_DriveFile([
                        'name' => $file->getName(),
                        'parents' => [$destinationId]
                    ]);
                    
                    try {
                        $this->driveService->files->copy($file->getId(), $fileMetadata);
                    } catch (Exception $e) {
                        // Log if a specific file fails, but continue
                        logger()->error("Failed to copy file: {$file->getName()} (ID: {$file->getId()}). Error: " . $e->getMessage());
                    }
                }
            }
            $pageToken = $response->getNextPageToken();
        } while ($pageToken);
    }
}
