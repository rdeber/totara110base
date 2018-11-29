<?php
// This file is part of The Bootstrap Moodle theme
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     theme_totara110base
 * @copyright   2014 Bas Brands, www.basbrands.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Bas Brands
 * @author      David Scotson
 * @author      Joby Harding <joby.harding@totaralearning.com>
 * @author      Petr Skoda <petr.skoda@totaralms.com>
 */

defined('MOODLE_INTERNAL') || die();

$knownregionpre = $PAGE->blocks->is_known_region('side-pre');
$knownregionpost = $PAGE->blocks->is_known_region('side-post');
$knownregionmain = $PAGE->blocks->is_known_region('main');

$grid = new theme_roots\output\bootstrap_grid();

if ($PAGE->blocks->region_has_content('side-pre', $OUTPUT)) {
    $grid->has_side_pre();
}

if ($PAGE->blocks->region_has_content('side-post', $OUTPUT)) {
    $grid->has_side_post();
}

$regions = $grid->get_regions_classes();

$PAGE->set_popup_notification_allowed(false);

$themerenderer = $PAGE->get_renderer('theme_totara110base');

// TODO improve on this legacy approach.
$hastotaramenu = false;
$totaramenu = '';
if (isloggedin() && empty($PAGE->layout_options['nocustommenu'])) {
    $menudata = totara_build_menu();
    $totara_core_renderer = $PAGE->get_renderer('totara_core');
    $totaramenu = $totara_core_renderer->totara_menu($menudata);
    $hastotaramenu = !empty($totaramenu);
}
// END

// Slidebar side.
$slidebarside = isset($PAGE->theme->settings->sidebarblockregionalignment) ?
    $PAGE->theme->settings->sidebarblockregionalignment :
    'left';

$themefiles = theme_totara110base_setting_files($PAGE->theme->settings);
if (!isset($PAGE->theme->settings->sidebarblockregionalignment)) {
    $PAGE->theme->settings->sidebarblockregionalignment = "left";
}

// Fetch additional body classes from settings.
$settingsbodyclasses = theme_totara110base_fetch_bodyclass_settings($PAGE->theme->settings);

// Fetch HTML for social media icons.
$socialicons = theme_totara110base_fetch_socialicons($PAGE->theme->settings);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/head.php"); ?>

<body <?php echo $OUTPUT->body_attributes('custom-theme '.$PAGE->theme->settings->themecolor.$settingsbodyclasses); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<!-- Main navigation -->
<?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/header.php"); ?>

<div id="sb-site" class="sb-slide">

    <!-- Breadcrumb and edit buttons -->
    <div class="breadcrumb-wrap">
        <div class="container-fluid breadcrumb-container">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo $OUTPUT->full_header(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div id="page" class="container-fluid">
        <div id="page-content" class="row">
            <div id="region-main" class="<?php echo $regions['content']; ?>">
                <?php
                if ($knownregionmain) {
                    echo $OUTPUT->blocks('main');
                }?>
                <?php echo $OUTPUT->course_content_header(); ?>
                <?php echo $OUTPUT->main_content(); ?>
                <?php echo $OUTPUT->course_content_footer(); ?>
            </div>

            <?php
            if ($knownregionpre) {
                echo $OUTPUT->blocks('side-pre', $regions['pre']);
            }?>
            <?php
            if ($knownregionpost) {
                echo $OUTPUT->blocks('side-post', $regions['post']);
            }?>
        </div>
    </div>

    <!-- Footer -->
    <?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/footer.php"); ?>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
<a href="#" class="scrollToTop"><span class="fa fa-caret-up"></span><?php echo get_string('scroll-to-top','theme_totara110base'); ?></a>
</body>
</html>
