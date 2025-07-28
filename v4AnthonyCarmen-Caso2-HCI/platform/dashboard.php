<?php
// platform/dashboard.php
require_once __DIR__ . '/../settings/config.php';
require_once __DIR__ . '/../settings/db.php';
require_once __DIR__ . '/../security/auth.php';
require_once __DIR__ . '/../settings/lang.php'; // <<<--- THIS IS THE KEY ADDITION

// Protect this page: redirect if not logged in
if (!isAuthenticated()) {
    redirect(BASE_URL . 'security/login.php');
}

include __DIR__ . '/../includes/header.php'; // Includes platform navigation
?>

<section class="dashboard-section">
    <h1><?= sprintf($lang['dashboard_welcome_title'] ?? 'Welcome to the %s platform', APP_NAME) ?></h1>
    <div class="text-center mb-4">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/YOUR_VIDEO_ID" 
                title="EduEventos Dashboard Tutorial" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    </div>
    <p><?= $lang['dashboard_intro_text'] ?? 'Your management system is divided into 3 sections, which you can:' ?></p>

    <div class="dashboard-actions">
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/view_icon2.jpg" alt="<?= $lang['action_view_info'] ?? 'View Additional Information' ?>">
            <p><strong><?= $lang['action_view_info'] ?? 'View Additional Information' ?></strong></p>
        </div>
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/edit_icon.jpg" alt="<?= $lang['action_edit_info'] ?? 'Edit Information' ?>">
            <p><strong><?= $lang['action_edit_info'] ?? 'Edit Information' ?></strong></p>
        </div>
        <div class="action-item">
            <img src="<?= BASE_URL ?>images/delete_icon.jpg" alt="<?= $lang['action_delete_row'] ?? 'Delete row' ?>">
            <p><strong><?= $lang['action_delete_row'] ?? 'Delete row' ?></strong> <?= $lang['action_delete_row_details'] ?? 'from the list' ?></p>
        </div>
        <div class="action_add_new">
            <img src="<?= BASE_URL ?>images/add_icon3.png" alt="<?= $lang['action_add_new'] ?? 'Add New' ?>">
            <p><strong><?= $lang['action_add_new'] ?? 'Add New' ?></strong> <?= $lang['action_add_new_details'] ?? '(Event/Location/Contact)' ?></p>
        </div>
    </div>

    <div class="section-summaries">
        <div class="summary-box">
            <h3><?= $lang['section_events_title'] ?? 'Events' ?></h3>
            <p><?= $lang['section_events_desc_1'] ?? 'Register events such as conferences, fairs, workshops, and seminars.' ?></p>
            <p><?= $lang['section_events_desc_2'] ?? 'Information: title, # of guests, date, time, timezone, repetition, reminder, event contact, event type, location, and description.' ?></p>
            <a href="<?= BASE_URL ?>platform/events.php" class="btn btn-success"><?= $lang['manage_events_btn'] ?? 'Manage Events' ?></a>
        </div>
        <div class="summary-box">
            <h3><?= $lang['section_locations_title'] ?? 'Locations' ?></h3>
            <p><?= $lang['section_locations_desc_1'] ?? 'Register places to hold events.' ?></p>
            <p><?= $lang['section_locations_desc_2'] ?? 'Information: title, address, city, country, and Google Maps link.' ?></p>
            <a href="<?= BASE_URL ?>platform/locations.php" class="btn btn-success"><?= $lang['manage_locations_btn'] ?? 'Manage Locations' ?></a>
        </div>
        <div class="summary-box">
            <h3><?= $lang['section_contacts_title'] ?? 'Contacts' ?></h3>
            <p><?= $lang['section_contacts_desc_1'] ?? 'Register contacts, who are responsible for the events.' ?></p>
            <p><?= $lang['section_contacts_desc_2'] ?? 'Information: first names, last names, educational institution, country, ID, phone number, email, and upload their profile picture.' ?></p>
            <a href="<?= BASE_URL ?>platform/contacts.php" class="btn btn-success"><?= $lang['manage_contacts_btn'] ?? 'Manage Contacts' ?></a>
        </div>
    </div>

    <p class="text-center" style="margin-top: 30px;">
        <small><?= $lang['footer_admin_contact'] ?? '*Contact your administrator for additional information or IT support' ?></small>
    </p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<style>
/* Your CSS here - it does not need translation */
.dashboard-section h1 {
    text-align: center;
    color: #4CAF50;
    margin-bottom: 30px;
}

.dashboard-section p {
    text-align: center;
    font-size: 1.1em;
    margin-bottom: 25px;
}

.dashboard-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
    margin-bottom: 50px;
    padding: 20px;
    background-color: #2c2c2c;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.action-item {
    text-align: center;
    flex: 1 1 200px;
    max-width: 250px;
}

.action-item img {
    height: 60px;
    margin-bottom: 10px;
}

.action-item p {
    margin: 0;
    font-size: 0.95em;
    color: #bbb;
}

.section-summaries {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 30px;
}

.summary-box {
    background-color: #2c2c2c;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    flex: 1 1 300px;
    max-width: 350px;
    text-align: center;
}

.summary-box h3 {
    color: #4CAF50;
    margin-top: 0;
    margin-bottom: 15px;
}

.summary-box p {
    font-size: 0.9em;
    line-height: 1.5;
    color: #ccc;
    text-align: left;
    min-height: 80px;
}

.summary-box .btn {
    margin-top: 20px;
}
</style>