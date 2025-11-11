// Quick links functionality
const quickLinks = {
    // Staff Leasing links
    backOfficeLink: "https://docs.google.com/forms/u/1/d/1R2RLrtSql_nzz9JgffJTeDYm3lyfz8eN7WEhHNdZMoA/viewform?usp=sharing&edit_requested=true",
    businessSupportLink: "https://docs.google.com/forms/u/0/d/1XBL6LfJvblk-xhv3oB32X_D9MPcoWYGmJUscU5MBVhU/viewform?usp=sharing&edit_requested=true",
    digitalMarketingLink: "https://docs.google.com/forms/u/0/d/1l6wmhGXx9dysy0v0XE8aH9xcA-_SxAD8RpI0uWCL4Bs/viewform?usp=sharing&edit_requested=true",
    creativeServicesLink: "https://docs.google.com/forms/d/1K5l-SdorU5tkkAeCXS6f4U2NSrqbQ1ewePv1ICNqfsA/viewform?edit_requested=true",
    techDevelopmentLink: "https://docs.google.com/forms/d/1zxlc-NELKCcq_lqs5BsWwyjGz3z_O6x7NAAJxNt1l20/viewform?edit_requested=true",
    specializedRolesLink: "https://docs.google.com/forms/d/1EhMZn7UpBawWjCLtIKCAzY5T6EaVsGk_pvV1Le9Ezvo/viewform?edit_requested=true",
    // SOD & Tele-Consultation links
    teleConsultationLink: "https://docs.google.com/forms/d/1hlIABgzp6QtwAyGWr7k7mNC47ygN0u4f6KYWqR9MuHw/viewform?edit_requested=true",
    // Service Marketplace links
    marketplaceServiceLink: "https://docs.google.com/forms/d/e/1FAIpQLScy8TrDYKV9_WjBISnakmWx1DLWQ9pZwkvicI7XkasZYHCKuw/viewform",

    //HR-MANAGEMENT
    incidentReportLink: "https://docs.google.com/forms/d/e/1FAIpQLSfBgO7Hvwr0RUNKzaIXDsuo-eY_Yl9yJec0zY_zAvZ5RUwUbw/viewform",
    nteRequestLink: "https://docs.google.com/forms/d/e/1FAIpQLSd5FcFD8Oliyz9W5SVoDHtjPAxzbBuuPDP4jCremsMoWVpTdw/viewform",
    nteSubmissionLink: "https://docs.google.com/forms/d/e/1FAIpQLSd5FcFD8Oliyz9W5SVoDHtjPAxzbBuuPDP4jCremsMoWVpTdw/viewform",
    adminHearingLink: "https://docs.google.com/forms/u/1/d/e/1FAIpQLScpg3_Larq4CtuC0rKCckrT8NvAHCK9iwt8MyyQCGL2-T4jeA/viewform?usp=header",
    noticeDecisionLink: "https://docs.google.com/forms/d/e/1FAIpQLSflydvOds3ZQaUWEOsE6fIpe9oFcE_HxdDlhft96w1v9929Vg/viewform",
    companyPolicyLink: "https://docs.google.com/forms/u/1/d/e/1FAIpQLSezvf8iG4I1Xaaa9mCuHsKunF5LQD4MP-rCdtB8lY2YaRbjA/viewform"
};

function copyQuickLink(linkKey) {
    const link = quickLinks[linkKey];
    if (link) {
        navigator.clipboard.writeText(link).then(() => {
            showToast('Link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy link: ', err);
            const textArea = document.createElement("textarea");
            textArea.value = link;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showToast('Link copied to clipboard!');
            } catch (err) {
                console.error('Fallback copy failed: ', err);
                showToast('Failed to copy link', 'error');
            }
            document.body.removeChild(textArea);
        });
    }
}

