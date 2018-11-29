<?php
// This file is part of Moodle - http://moodle.org/
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
 * @package   theme_totara110base
 * @copyright 2016 Moodle, moodle.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function theme_totara110base_page_init(moodle_page $page) {
    $page->requires->jquery();
}

/**
 * Parses CSS before it is cached.
 *
 * This function can make alterations and replace patterns within the CSS.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The parsed CSS The parsed CSS.
 */
function theme_totara110base_process_css($css, $theme) {
    // Set custom CSS.
    if (!empty($theme->settings->overridecss)) {
        $customcss = $theme->settings->overridecss;
    } else {
        $customcss = null;
    }
    // Add enhanced grid language string.
    $tag = '[[language:enhancedgridhovertext]]';
    $replacement = get_string('enhancedgridhovertext', 'theme_totara110base');
    if (is_null($replacement)) {
        $replacement = '';
    }
    $css = str_replace($tag, $replacement, $css);

    if (!empty($theme->settings->themecolor)) {
        $css = theme_totara110base_set_color_palette($css, $theme->settings->themecolor);
    }

    $css = theme_totara110base_set_customcss($css, $customcss);

    return $css;
}

/**
 * Adds color-specific css to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $themecolor The current color setting for the theme.
 * @return string The CSS which now contains the specific color css.
 */
function theme_totara110base_set_color_palette($css, $themecolor) {
    global $CFG;
    $themecolorfile = str_replace("-theme", "-noprocess.css", $themecolor);
    $colorcssfile = $CFG->dirroot.'/theme/totara110base/style/'.$themecolorfile;
    if (file_exists($colorcssfile)) {
        $colorcss = @file_get_contents($colorcssfile);
        $css .= $colorcss;
    }
    return $css;
}

/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_totara110base_set_customcss($css, $customcss) {
    $allcustomcss = "";
    // Load custom CSS file contents if it exists.
    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_totara110base', 'overridecssfile');
    foreach ($files as $file) {
        $file = $fs->get_file($file->get_contextid(), $file->get_component(), $file->get_filearea(),
            $file->get_itemid(), $file->get_filepath(), $file->get_filename());
        if ($file) {
            $allcustomcss .= $file->get_content();
        }
    }
    // Add in custom css entered in text area.
    $allcustomcss .= $customcss;
    if (is_null($allcustomcss)) {
        $allcustomcss = '';
    }
    $css .= $allcustomcss;
    return $css;
}

/**
 * Finds files in theme settings and returns moodle urls for those files.
 *
 * @return array Moodle urls for theme files
 */
function theme_totara110base_setting_files($settings) {
    $context = context_system::instance();
    $settingsfiles = [];
    $fs = get_file_storage();
    foreach ($settings as $key => $filename) {
        $files = $fs->get_area_files($context->id, 'theme_totara110base', $key);
        foreach ($files as $file) {
            if ($filename == $file->get_filepath().$file->get_filename()) {
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                    $file->get_itemid(), $file->get_filepath(), $filename);
                $settingsfiles[$key] = $url;
            }
        }
    }
    return $settingsfiles;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_totara110base_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('totara110base');
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Returns any additional body classes from settings to be written to the layout body tag.
 *
 * @param object $settings The theme settings object
 * @return string String of body classes to be added to the body tag in layout file.
 */
function theme_totara110base_fetch_bodyclass_settings($settings) {
    // If all of the settings are boolean, we can eventually abstract this into a foreach, so placeholer below.
    // Array of settings to check. Add settings to this array.
    $bodyclasses = '';
    if (!empty($settings->coursecatajax)) {
        if ($settings->coursecatajax == 1) {
            // Fabricate settings class string.
            $settingsclass = ' totara110base-settings-'.'coursecatajax';
            // Add body class to the bodyclasses.
            $bodyclasses .= $settingsclass;
        }
    }
    if (!empty($settings->onetopicstyle)) {
        // Fabricate settings class string.
        $settingsclass = ' totara110base-settings-'.$settings->onetopicstyle;
        // Add body class to the bodyclasses.
        $bodyclasses .= $settingsclass;
    }
    if (!empty($settings->gridstyle)) {
        if ($settings->gridstyle == 1) {
            // Fabricate settings class string.
            $settingsclass = ' totara110base-settings-gridenhanced';
            // Add body class to the bodyclasses.
            $bodyclasses .= $settingsclass;
        }
    }
    if (!empty($settings->replaceimgicons)) {
        if ($settings->replaceimgicons == 1) {
            // Fabricate settings class string.
            $settingsclass = ' totara110base-settings-replaceimgicons';
            // Add body class to the bodyclasses.
            $bodyclasses .= $settingsclass;
        }
    }
    // Return classes.
    return $bodyclasses;
}

/**
 * Returns the favicon markup.
 * If a custom favicon is not uploaded in the settings area, the default favicon in the theme is used.
 *
 * @param object $settings The theme settings object
 * @return string String of html that sets the favicon used in the html head.
 */
function theme_totara110base_fetch_favicon($settings) {
    global $OUTPUT;
    $themefiles = theme_totara110base_setting_files($settings);
    if (isset($themefiles['favicon'])) {
        $faviconhtml = '<link rel="shortcut icon" href="'.$themefiles['favicon'].'" />';
    } else {
        $faviconhtml = '<link rel="shortcut icon" href="'.$OUTPUT->favicon().'" />';
    }
    // Return favicon html.
    return $faviconhtml;
}

/**
 * Returns sidebar toggle button html.
 *
 * @param object $settings The theme settings
 * @return string String of menu toggle button.
 */
function theme_totara110base_fetch_sidebar_toggle_button($settings) {
    global $PAGE;
    if (in_array($PAGE->pagelayout, ['secure'])) {
        return '';
    }
    $button = "";
    // Ensure defaults are set.
    if (empty($settings->sidebarblockregionalignment)) {
        $settings->sidebarblockregionalignment = 'left';
    }
    if (empty($settings->sidebarblockregionbuttontype)) {
        $settings->sidebarblockregionbuttontype = 'icontext';
    }
    $button = '<a id="side-panel-button" class="side-panel-button sb-toggle-'.$settings->sidebarblockregionalignment.'" href="#">';
    if ($settings->sidebarblockregionbuttontype == 'icon' || $settings->sidebarblockregionbuttontype == 'icontext') {
        $button .= '<div id="hamburger">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="screen-reader-text">Reveal Off-Canvas Navigation</span>
                    </div>';
    }
    if ($settings->sidebarblockregionbuttontype == 'text' || $settings->sidebarblockregionbuttontype == 'icontext') {
        $button .= '<div href="#" class="optional-display-text">'.get_string('sidebarblockregiontogglelabel', 'theme_totara110base').'</div>';
    }
    $button .= '</a>';
    return $button;
}

/**
 * Returns banner content, either video or slider background, based on settings.
 * If a path for an MP4 video is set, returns the video background, otherwise slider.
 *
 * @param object $settings The theme settings object
 * @return string String of HTML to be written to frontpage.php.
 */
function theme_totara110base_fetch_banner($settings) {
    $themeimages = theme_totara110base_setting_files($settings);
    // Declare var for banner html content.
    $bannerhtml = '';

    // Set demo settings if demomode is null/on.
    if (!isset($settings->demomode) || $settings->demomode == 'on') {
        global $OUTPUT;
        $themeimages['sliderimage1'] = $OUTPUT->pix_url('demo-slide1', 'theme');
        $settings->sliderheader1 = "Welcome to totara110base";
        $settings->slidertext1 = "totara110base is a modern Moodle® theme designed by Remote-Learner for Remote-Learner clients.";
        $settings->sliderbuttonlink1 = "http://www.remote-learner.net/welcome-to-totara110base";
        $settings->sliderbuttonlabel1 = "Learn More";

        $themeimages['sliderimage2'] = $OUTPUT->pix_url('demo-slide2', 'theme');
        $settings->sliderheader2 = "Welcome to totara110base";
        $settings->sliderbuttonlink2 = "http://www.remote-learner.net/totara110base";
        $settings->slidertext2 = "totara110base is a modern Moodle® theme designed by Remote-Learner for Remote-Learner clients.";
        $settings->sliderbuttonlabel2 = "Learn More";
    }

    // Mp4 setting and length.
    $mp4 = '';
    if (isset($settings->videobackgroundmp4) && isset($settings->demomode) && $settings->demomode == 'off') {
        $mp4 = $settings->videobackgroundmp4;
    }
    $mp4length = strlen($mp4);
    // Vid background image.
    if (isset($themeimages['videoimage'])) {
        $videoimage = $themeimages['videoimage'];
    }

    if (!empty($mp4)) {
        if ($mp4length >= 4) {
            $bannerhtml .= '<div class="video-hero jquery-background-video-wrapper demo-video-wrapper">';
            $bannerhtml .= '<video class="jquery-background-video"
                    autoplay
                    muted
                    loop ';

            // Print videoimage if available.
            if (isset($videoimage)) {
                $bannerhtml .= 'poster="'.$videoimage.'">';
            } else {
                $bannerhtml .= '>';
            }

            // Print mp4.
            $bannerhtml .= '<source src="'. $mp4 . '" type="video/mp4">';

            // If webm, print webm.
            if (!empty($settings->videobackgroundwebm)) {
                $bannerhtml .= '<source src="'. $settings->videobackgroundwebm . '" type="video/webm">';
            }

            // If ogg, print ogg.
            if (!empty($settings->videobackgroundogg)) {
                $bannerhtml .= '<source src="'. $settings->videobackgroundogg . '" type="video/ogg">';
            }

            $bannerhtml .= '</video>';

            $bannerhtml .= '<div class="video-overlay"></div>';
            $bannerhtml .= '<div class="page-width">';
            $bannerhtml .= '<div class="video-hero--content">';

            // If header, print header.
            if (!empty($settings->videoheader)) {
                $bannerhtml .= '<h2>'. $settings->videoheader . '</h2>';
            }

            // If text, print text.
            if (!empty($settings->videotext)) {
                $bannerhtml .= '<p>'. $settings->videotext . '</p>';
            }

            $bannerhtml .= '</div>';
            $bannerhtml .= '</div>';
            $bannerhtml .= '</div>';
        }
    } else {
        // If no mp4 video path, return slider markup.
        $bannerhtml .= '<div class="slider-wrapper">';
        if (isset($settings->pausesetting)) {
            $pausetime = $settings->pausesetting * 1000;
        } else {
            $pausetime = 4000;
        }
        $bannerhtml .= '<div id="slider" data-pausetime="'.$pausetime.'">';

        for ($i = 1; $i <= 4; $i++) {
            if (isset($themeimages['sliderimage'.$i])) {
                $current = '';
                if ($i == 1) {
                    $current = ' current';
                }
                $bannerhtml .= '<div class="slide'.$i.$current.'">';
                $bannerhtml .= html_writer::empty_tag('img', array('alt' => 'Banner'.$i, 'src' => $themeimages['sliderimage'.$i]));
                if (strlen ($settings->{'slidertext'.$i}) > 0) {
                    $bannerhtml .= '<div class="banner-title">';
                    $bannerhtml .= '<div class="banner-title-text">';
                    $bannerhtml .= '<h1>'.$settings->{"sliderheader".$i}.'</h1>';
                    $bannerhtml .= '<p>'.$settings->{'slidertext'.$i}.'</p>';
                    $bannerhtml .= '<a href="'.$settings->{'sliderbuttonlink'.$i}.'">';
                    $bannerhtml .= '<span class="fa fa-chevron-circle-right"></span> ';
                    $bannerhtml .= $settings->{'sliderbuttonlabel'.$i};
                    $bannerhtml .= '</a>';
                    $bannerhtml .= '</div>';
                    $bannerhtml .= '</div>';
                }
                $bannerhtml .= '</div>';
            }
        }
        $bannerhtml .= '</div>';
        $bannerhtml .= '<div id="slider-direction-nav"></div>';
        $bannerhtml .= '<div id="slider-control-nav"></div>';
        $bannerhtml .= '</div>';
    }

    // Return html.
    return $bannerhtml;
}

/**
 * Returns sub-banner content based on settings.
 *
 * @param object $settings The theme settings object
 * @return string String of HTML to be written to frontpage.php.
 */
function theme_totara110base_fetch_subbanner($settings) {
    $numsections = 0;
    $subbannerhtml = '';

    // Set demo settings if demomode is on.
    if (!isset($settings->demomode) || $settings->demomode == 'on') {
        $settings->subsectiontitle1 = "Explore";
        $settings->subsectionlink1 = "http://www.remote-learner.net/explore-totara110base";
        $settings->subsectionicon1 = "map-o";
        $settings->subsectiondescription1 = "Explore the features of the totara110base theme.";
        $settings->subsectionlabel1 = "Click Here";

        $settings->subsectiontitle2 = "Customize";
        $settings->subsectionlink2 = "http://www.remote-learner.net/configure-totara110base";
        $settings->subsectionicon2 = "map-marker";
        $settings->subsectiondescription2 = "Learn how to customize the totara110base theme.";
        $settings->subsectionlabel2 = "Click Here";

        $settings->subsectiontitle3 = "Innovate";
        $settings->subsectionlink3 = "http://www.remote-learner.net/gallery/";
        $settings->subsectionicon3 = "map-signs";
        $settings->subsectiondescription3 = "Find inspiration in the RL themes gallery.";
        $settings->subsectionlabel3 = "Click Here";
    }

    // Determine the number of sections.
    for ($i = 1; $i <= 3; $i++) {
        if (!empty($settings->{"subsectiontitle".$i})) {
            $numsections++;
        }
    }

    if ($numsections > 0) {
        // Get span class.
        $spanwidth = 12 / $numsections;
        $spanclass = "span".$spanwidth;

        // Add subbanner html.
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($settings->{"subsectiontitle".$i})) {
                $subbannerhtml .= '<div class="'.$spanclass.' action-block">';
                $subbannerhtml .= '<div class="block">';
                if (!empty($settings->{'subsectionlink'.$i})) {
                    $subbannerhtml .= '<a href="'.$settings->{'subsectionlink'.$i}.'" class="action">';
                }
                $subbannerhtml .= '<div class="content">';
                $subbannerhtml .= '<div class="action-content">';
                $subbannerhtml .= '<div class="hi-icon-wrap hi-icon-effect-1 hi-icon-effect-1a">';
                if (empty($settings->{'subsectionicon'.$i})) {
                    $settings->{'subsectionicon'.$i} = 'genderless';
                }
                $subbannerhtml .= '<div class="hi-icon">';
                $subbannerhtml .= '<span class="fa fa-'.$settings->{'subsectionicon'.$i}.'"></span>';
                $subbannerhtml .= '</div>';
                $subbannerhtml .= '</div>';
                $subbannerhtml .= '<h2>'.$settings->{'subsectiontitle'.$i}.'</h2>';
                $subbannerhtml .= '<p>'.$settings->{'subsectiondescription'.$i}.'</p>';
                $subbannerhtml .= '</div>';
                if (!empty($settings->{'subsectionlink'.$i}) && !empty($settings->{'subsectionlabel'.$i})) {
                    $subbannerhtml .= '<p>';
                    $subbannerhtml .= '<button tabindex="-1" href="'.$settings->{'subsectionlink'.$i}.'">';
                    $subbannerhtml .= '<span class="fa fa-chevron-circle-right"></span>';
                    $subbannerhtml .= $settings->{'subsectionlabel'.$i};
                    $subbannerhtml .= '</button>';
                    $subbannerhtml .= '</p>';
                }
                $subbannerhtml .= '</div>';
                if (!empty($settings->{'subsectionlink'.$i})) {
                    $subbannerhtml .= '</a>';
                }
                $subbannerhtml .= '</div>';
                $subbannerhtml .= '</div>';
            }
        }

        // Return html.
        return $subbannerhtml;
    }
}

/**
 * Returns social icons content based on settings.
 *
 * @param object $settings The theme settings object
 * @return string String of HTML to be written to layout files.
 */
function theme_totara110base_fetch_socialicons($settings) {
    global $PAGE;
    // For secure pages no links are allowed out side of Moodle.
    if ($PAGE->pagelayout == 'secure') {
        return '';
    }
    $numsections = 0;
    $socialiconshtml = '';
    if (!empty($settings->socialmediaicons)) {
        $socialicons = explode("\n", $settings->socialmediaicons);
        foreach ($socialicons as $socialicon) {
            $socialiconparts = explode("|", $socialicon);
            if (!empty($socialiconparts[0]) && !empty($socialiconparts[1])) {
                $socialiconshtml .= '<a href="'.$socialiconparts[1].'" target="_blank" title="'.$socialiconparts[0].'"><i class="fa fa-'.$socialiconparts[0].'"></i></a>';
            }
        }
    }

    // Return html.
    return $socialiconshtml;
}


