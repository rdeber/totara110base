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
//removed all references to side-post region to maintain Totara110base's 2-column layout
//$knownregionpost = $PAGE->blocks->is_known_region('side-post');

$grid = new theme_roots\output\bootstrap_grid();

if ($PAGE->blocks->region_has_content('side-pre', $OUTPUT)) {
    $grid->has_side_pre();
}

//removed all references to side-post region to maintain Totara110base's 2-column layout
//if ($PAGE->blocks->region_has_content('side-post', $OUTPUT)) {
//    $grid->has_side_post();
//}

$regions = $grid->get_regions_classes();

$PAGE->set_popup_notification_allowed(false);

$themerenderer = $PAGE->get_renderer('theme_totara110base');

// TODO improve on this legacy approach.
$hastotaramenu = false;
$totaramenu = '';
if (empty($PAGE->layout_options['nocustommenu'])) {
    $menudata = totara_build_menu();
    $totara_core_renderer = $PAGE->get_renderer('totara_core');
    $totaramenu = $totara_core_renderer->totara_menu($menudata);
    $hastotaramenu = !empty($totaramenu);
}
// END

$themefiles = theme_totara110base_setting_files($PAGE->theme->settings);
if (!isset($PAGE->theme->settings->sidebarblockregionalignment)) {
    $PAGE->theme->settings->sidebarblockregionalignment = "left";
}

// Fetch additional body classes from settings.
$settingsbodyclasses = theme_totara110base_fetch_bodyclass_settings($PAGE->theme->settings);

// Fetch HTML for social media icons.
$socialicons = theme_totara110base_fetch_socialicons($PAGE->theme->settings);

// Fetch HTML for banner background, either slider or video.
$banner = theme_totara110base_fetch_banner($PAGE->theme->settings);

// Slidebar side.
$slidebarside = isset($PAGE->theme->settings->sidebarblockregionalignment) ?
    $PAGE->theme->settings->sidebarblockregionalignment :
    'left';

// Fetch HTML for sub-banner.
$subbanner = theme_totara110base_fetch_subbanner($PAGE->theme->settings);
$enable1alert = 0;
$enable2alert = 0;
$enable3alert = 0;

if (isset($PAGE->theme->settings->enablealert)  && $PAGE->theme->settings->enablealert == 1) {
    $enable1alert =1;
}
if (isset($PAGE->theme->settings->enable2alert)  && $PAGE->theme->settings->enable2alert == 1) {
    $enable2alert =1;
}
if (isset($PAGE->theme->settings->enable3alert)  && $PAGE->theme->settings->enable3alert == 1) {
    $enable3alert =1;
}
if ($enable1alert || $enable2alert || $enable3alert) {
    $alertinfo = '<span class="fa-stack alerticon"><span aria-hidden="true" class="fa fa-info fa-stack-1x "></span></span>';
    $alertdanger = '<span class="fa-stack alerticon"><span aria-hidden="true" class="fa fa-warning fa-stack-1x "></span></span>';
    $alertsuccess = '<span class="fa-stack alerticon"><span aria-hidden="true" class="fa fa-bullhorn fa-stack-1x "></span></span>';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>

<?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/head.php"); ?>

<body <?php echo $OUTPUT->body_attributes('custom-theme '.$PAGE->theme->settings->themecolor.$settingsbodyclasses); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>


<!-- Main navigation -->
<?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/header.php"); ?>

<div id="sb-site" class="sb-slide">

    <!-- Banner Slider -->
    <section id="banner-wrap">
        <?php echo $banner ?>
    </section>

    <!-- Welcome Block -->
    <section id="welcome-block" class="fp-section">
        <div class="container-fluid">
            <div class="row-fluid">
                <?php echo $OUTPUT->blocks('welcome-block', 'col-xs-12'); ?>
            </div>
        </div>
    </section>

    <!-- Action Blocks -->
    <section id="action-blocks" class="fp-section">
        <div class="container-fluid">
            <div class="row-fluid">
                <?php echo $subbanner ?>
            </div>
        </div>
    </section>

    <!-- Action Blocks -->
    <section id="fullwidth-block" class="fp-section">
        <div class="container-fluid">
            <div class="row-fluid">
                <?php
                    //Start Alerts
                    //Alert #1
                    if ($enable1alert) { ?>
                        <div class="alert alert-warning alert-dismissible useralerts alert-<?php echo $PAGE->theme->settings->alert1type; ?>" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="fa fa-times-circle"></span></button>
                        <?php
                        $alert1icon = 'alert' . $PAGE->theme->settings->alert1type;
                        echo $$alert1icon.'<span class="title"><b>'. $PAGE->theme->settings->alert1title;
                        echo '</b> </span><p>'.$PAGE->theme->settings->alert1text. '</p>';?>
                        </div>
                        <?php
                    }
                    //Alert #2 -->
                    if ($enable2alert) { ?>
                        <div class="alert alert-warning alert-dismissible useralerts alert-<?php echo $PAGE->theme->settings->alert2type; ?>" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="fa fa-times-circle"></span></button>
                        <?php
                        $alert2icon = 'alert' . $PAGE->theme->settings->alert2type;
                        echo $$alert2icon.'<span class="title"><b>'. $PAGE->theme->settings->alert2title;
                        echo '</b> </span><p>'.$PAGE->theme->settings->alert2text .'</p>';?>
                        </div>
                        <?php
                    }
                    //Alert #3
                    if ($enable3alert) { ?>
                        <div class="alert alert-warning alert-dismissible useralerts alert-<?php echo $PAGE->theme->settings->alert3type; ?>" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="fa fa-times-circle"></span></button>
                        <?php
                        $alert3icon = 'alert' . $PAGE->theme->settings->alert3type;
                        echo $$alert3icon.'<span class="title"><b>'. $PAGE->theme->settings->alert3title;
                        echo '</b> </span><p>' . $PAGE->theme->settings->alert3text.'</p>'; ?>
                        </div>
                        <?php
                    }
                ?>
                <?php echo $OUTPUT->blocks('action-full', 'col-xs-12'); ?>
            </div>
        </div>
    </section>

    <!-- Content -->
    <div id="page" class="container-fluid">
        <div id="page-content" class="row">
            <div id="region-main" class="<?php echo $regions['content']; ?>">
                <?php echo $OUTPUT->course_content_header(); ?>
                <?php echo $OUTPUT->main_content(); ?>
                <?php echo $OUTPUT->course_content_footer(); ?>
            </div>

            <?php
            if ($knownregionpre) {
                echo $OUTPUT->blocks('side-pre', $regions['pre']);
            }?>

        </div>
    </div>

    <!-- Footer -->
    <?php require("{$CFG->dirroot}/theme/totara110base/layout/partials/footer.php"); ?>

</div>
<a href="#" class="scrollToTop"><span class="fa fa-caret-up"></span><?php echo get_string('scroll-to-top','theme_totara110base'); ?></a>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
