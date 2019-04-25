<?php

/*
 * *****************************************************************************
 * Contributions to this work were made on behalf of the GÉANT project, a 
 * project that has received funding from the European Union’s Framework 
 * Programme 7 under Grant Agreements No. 238875 (GN3) and No. 605243 (GN3plus),
 * Horizon 2020 research and innovation programme under Grant Agreements No. 
 * 691567 (GN4-1) and No. 731122 (GN4-2).
 * On behalf of the aforementioned projects, GEANT Association is the sole owner
 * of the copyright in all material which was developed by a member of the GÉANT
 * project. GÉANT Vereniging (Association) is registered with the Chamber of 
 * Commerce in Amsterdam with registration number 40535155 and operates in the 
 * UK as a branch of GÉANT Vereniging.
 * 
 * Registered office: Hoekenrode 3, 1102BR Amsterdam, The Netherlands. 
 * UK branch address: City House, 126-130 Hills Road, Cambridge CB2 1PQ, UK
 *
 * License: see the web/copyright.inc.php file in the file structure or
 *          <base_url>/copyright.php after deploying the software
 */

namespace web\skins\modern;
use web\lib\user;

/**
 * This class delivers various <div> elements for the front page.
 * 
 * @author Tomasz Wolniewicz <twoln@umk.pl>
 */
class Divs {

    /**
     * The Gui object we are working with.
     * 
     * @var user\Gui
     */
    private $Gui;

    public function __construct(user\Gui $Gui) {
        $this->Gui = $Gui;
    }

    public function divHeading($visibility = 'all') {
        $selectedLang = $this->Gui->languageInstance->getLang();
        $menu = new Menu($visibility, $selectedLang);
        $retval = "<div id='heading'>";
        $location = $this->Gui->skinObject->findResourceUrl("IMAGES", "consortium_logo.png");
        if ($location !== FALSE) {
            $retval .= "<div id='cat_logo'>
            <a href='" . \config\ConfAssistant::CONSORTIUM['homepage'] . "'><img id='logo_img' src='$location' alt='Consortium Logo'/></a>
            <span>Configuration Assistant Tool</span>
            </div>";
        }
        $retval .= "<div id='motd'>" . (isset(\config\Master::APPEARANCE['MOTD']) ? \config\Master::APPEARANCE['MOTD'] : '&nbsp') . "</div>";
        $loc2 = $this->Gui->skinObject->findResourceUrl("IMAGES", "icons/menu.png");
        if ($loc2 !== FALSE) {
            $retval .= "<img id='hamburger' src='$loc2' alt='Menu'/>";
        }
        $retval .= "<div id='menu_top'>";
        if ($visibility === 'start') {
            $retval .= $menu->printMinimalMenu();
        } else {
            $retval .= $menu->printMenu();
        }
        $retval .= "</div></div>\n";
        return $retval;
    }

    public function divUserWelcome() {
        $retval = "
<div id='user_welcome'> <!-- this information is shown just before the download -->
    <strong>" . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_PAGEHEADING] . "</strong>
    <p>
    <span id='download_info'>
    <!-- the empty href is dynamically exchanged with the actual path by jQuery at runtime -->
        " . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_DOWNLOAD] . "
    </span>
    <p>" . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_HEADING] . "
    <br/>
    <br/>";
        switch (\config\ConfAssistant::CONSORTIUM['name']) {
            case "eduroam": $retval .= $this->Gui->textTemplates->templates[user\EDUROAM_WELCOME_ADVERTISING];
                break;
            default:
        }
        $retval .= "
    </p>
    <p>" . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_USAGE] . "
    <p>" . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_PROBLEMS] . "
    </p>
    <p>
    <a href='javascript:back_to_downloads()'><strong>" . $this->Gui->textTemplates->templates[user\WELCOME_ABOARD_BACKTODOWNLOADS] . "</strong></a>
    </p>
</div> <!-- id='user_welcomer_page' -->
";
        return $retval;
    }

    public function divSilverbullet() {
        $retval = "
<div id='silverbullet'>"
                . $this->Gui->textTemplates->templates[user\SB_GO_AWAY] .
                "</div>
    ";
        return $retval;
    }

    public function divTopWelcome() {
        $retval = '';
        if (\config\ConfAssistant::CONSORTIUM['name'] == "eduroam" && isset(\config\ConfAssistant::CONSORTIUM['deployment-voodoo']) && \config\ConfAssistant::CONSORTIUM['deployment-voodoo'] == "Operations Team") { // SW: APPROVED
            $retval = "<br><div id='top_invite_ad'>".$this->Gui->textTemplates->templates[user\FRONTPAGE_EDUROAM_AD]."</div>";
        }
        return "
<div id='welcome_top1'>
    " . $this->Gui->textTemplates->templates[user\HEADING_TOPLEVEL_GREET] . "
</div>
<div id='top_invite'>
    " . $this->Gui->textTemplates->templates[user\HEADING_TOPLEVEL_PURPOSE] . $retval . "
</div>";
    }

    public function divRoller() {
        $retval = "
<div id='roller'>
    <div id='slides'>
        <span id='line1'>" . $this->Gui->textTemplates->templates[user\FRONTPAGE_ROLLER_EASY] . "</span>
        <span id='line2'></span>
        <span id='line3'></span>
        <span id='line4'>";

        if (\config\Master::FUNCTIONALITY_LOCATIONS['CONFASSISTANT_RADIUS'] == "LOCAL") {
            $retval .= $this->Gui->textTemplates->templates[user\FRONTPAGE_ROLLER_CUSTOMBUILT];
        } elseif (\config\Master::FUNCTIONALITY_LOCATIONS['CONFASSISTANT_SILVERBULLET'] == "LOCAL") {
            $retval .= $this->Gui->textTemplates->templates[user\SB_FRONTPAGE_ROLLER_CUSTOMBUILT];
        }

        $retval .= "</span>
        <span id='line5'>";
        if (!empty(\config\ConfAssistant::CONSORTIUM['signer_name'])) {
            $retval .= $this->Gui->textTemplates->templates[user\FRONTPAGE_ROLLER_SIGNEDBY];
        }
        $retval .= "
        </span>
    </div>";
        $rollLocation = $this->Gui->skinObject->findResourceUrl("IMAGES", "empty.png");
        if ($rollLocation !== FALSE) {
            $retval .= "<div id = 'img_roll'>
                <img id='img_roll_0' src='$rollLocation' alt='Rollover 0'/> <img id='img_roll_1' src='$rollLocation' alt='Rollover 1'/>
            </div>";
        }
        $retval .= "</div>";
        return $retval;
    }

    public function divMainButton() {
        $retval = "<div id='user_button_td'>";
        $retval .= "<span id='signin'>
     <button class='large_button signin signin_large' id='user_button1'>
        <span id='user_button'>";
        if (\config\Master::FUNCTIONALITY_LOCATIONS['CONFASSISTANT_RADIUS'] == "LOCAL") {
            $retval .= $this->Gui->textTemplates->templates[user\FRONTPAGE_BIGDOWNLOADBUTTON];
        } elseif (\config\Master::FUNCTIONALITY_LOCATIONS['CONFASSISTANT_SILVERBULLET'] == "LOCAL") {
            $retval .= $this->Gui->textTemplates->templates[user\SB_FRONTPAGE_BIGDOWNLOADBUTTON];
        }

        $retval .= "
        </span>
     </button>
  </span>
  <span style='padding-left:50px'>&nbsp;</span>
</div>";
        return $retval;
    }

    public function divProfiles() {
        return "
<div id='profiles'> <!-- this is the profile selection filled during run time -->
    <div id='profiles_h' class='sub_h'>" . $this->Gui->textTemplates->templates[user\PROFILE_SELECTION] . "
    </div>" .
                "<select id='profile_list'></select><div id='profile_desc' class='profile_desc'></div>" .
                "</div>";
    }

    public function divPagetitle($mainText, $extraText = '') {
        return "
<div id='institution_name'>
    <span id='inst_name_span'>$mainText</span> <div id='inst_extra_text'>$extraText</div> 
</div>";
    }

    public function divInstitution($selectButton = TRUE) {
        $retval = "<div id='institution_name'>
    <span id='inst_name_span'></span> <div id='inst_extra_text'></div><!-- this will be filled with the IdP name -->" .
                ($selectButton ? "<a  id='select_another' class='signin' href=\"\">" . $this->Gui->textTemplates->templates[user\INSTITUTION_SELECTION] . "</a>" : "") .
                "</div>";
        $retval .= $this->emptyImage('idp_logo', 'IdP Logo');
        return $retval;
    }

    public function divFederation() {
        $retval = $this->emptyImage('fed_logo', 'Federation Logo');
        return $retval;
    }

    public function divOtherinstallers() {
        $retval = "
<div class='sub_h'>
    <div id='other_installers'>" . $this->Gui->textTemplates->templates[user\DOWNLOAD_CHOOSE] . "
         <table id='device_list' style='padding:0px;'>";

        foreach ($this->Gui->listDevices(isset($_REQUEST['hidden']) ? $_REQUEST['hidden'] : 0) as $group => $deviceGroup) {
            $groupIndex = count($deviceGroup);
            $deviceIndex = 0;

            $imgTag = "";
            $imgLocation = $this->Gui->skinObject->findResourceUrl("IMAGES", "vendorlogo/" . $group . ".png");
            if ($imgLocation !== FALSE) {
                $imgTag = '<img src="' . $imgLocation . '" alt="' . $group . ' Device" title="' . $group . ' Device">';
            }
            $retval .= '<tbody><tr><td class="vendor" rowspan="' . $groupIndex . '">' . $imgTag . '</td>';
            foreach ($deviceGroup as $d => $D) {
                if ($deviceIndex) {
                    $retval .= '<tr>';
                }
                $retval .= "<td><button id='" . $d . "'>" . $D['display'] . "</button>"
                        . "<div class='device_info' id='info_" . $d . "'></div></td>"
                        . "<td><button class='more_info_b' id='info_b_" . $d . "'>i</button></td></tr>\n";
                $deviceIndex++;
            }
            $retval .= "</tbody>";
        }

        $retval .= "    
        </table>
    </div>
</div>";
        return $retval;
    }

    public function divGuessOs($operatingSystem) {
        $vendorlogo = $this->Gui->skinObject->findResourceUrl("IMAGES", "vendorlogo/" . $operatingSystem['group'] . ".png");
        $vendorstyle = "";
        if ($vendorlogo !== FALSE) {
            $vendorstyle = "style='background-image:url(\"" . $vendorlogo . "\")'";
        }
        $deleteIcon = $this->Gui->skinObject->findResourceUrl("IMAGES", "icons/delete_32.png");
        $deleteImg = "";
        if ($deleteIcon !== FALSE) {
            $deleteImg = "<img id='cross_icon_" . $operatingSystem['device'] . "' src='$deleteIcon' >";
        }
        return "
<div class='sub_h' id='guess_os'>
    <!-- table browser -->
    <table id='browser'>
        <tr>
            <td>
                <button class='large_button guess_os' $vendorstyle id='g_" . $operatingSystem['device'] . "'>
                    $deleteImg
                    <div class='download_button_text_1' id='download_button_header_" . $operatingSystem['device'] . "'> " . $this->Gui->textTemplates->templates[user\DOWNLOAD_MESSAGE] . "
                    </div>
                    <div class='download_button_text'>" .
                $operatingSystem['display'] . "
                    </div>
                </button>
                <div class='device_info' id='info_g_" . $operatingSystem['device'] . "'></div>
          </td>
          <td style='vertical-align:top'>
               <button class='more_info_b large_button' id='g_info_b_" . $operatingSystem['device'] . "'>i</button>
          </td>
      </tr>
    </table> <!-- id='browser' -->
    <div class='sub_h'>
       <a href='javascript:other_installers()'>" . $this->Gui->textTemplates->templates[user\DOWNLOAD_CHOOSE] . "</a>
    </div>
</div> <!-- id='guess_os' -->";
    }

    public function divFooter() {
        $retval = "
<div class='footer' id='footer'>
    <table>
        <tr>
            <td>" .
                $this->Gui->catCopyright
                . "
            </td>";

        if (!empty(\config\Master::APPEARANCE['privacy_notice_url'])) {
            $retval .= "<td><a href='" . \config\Master::APPEARANCE['privacy_notice_url'] . "'>" . sprintf(_("%s Privacy Notice"), \config\ConfAssistant::CONSORTIUM['display_name']) . "</a></td>";
        }
        $retval .= "<td>";
        if (\config\ConfAssistant::CONSORTIUM['name'] == "eduroam" && isset(\config\ConfAssistant::CONSORTIUM['deployment-voodoo']) && \config\ConfAssistant::CONSORTIUM['deployment-voodoo'] == "Operations Team") {
            $geant = $this->Gui->skinObject->findResourceUrl("IMAGES", "dante.png");
            $eu = $this->Gui->skinObject->findResourceUrl("IMAGES", "eu.png");
            if ($geant !== FALSE && $eu !== FALSE) {
                $retval .= "<span id='logos'><img src='$geant' alt='GEANT' style='height:23px;width:47px'/>
              <img src='$eu' alt='EU' style='height:23px;width:27px;border-width:0px;'/></span>";
            }
            $retval .= "<span id='eu_text' style='text-align:right; padding-left: 60px; display: block; '><a href='http://ec.europa.eu/dgs/connect/index_en.htm' style='text-decoration:none; vertical-align:top; text-align:right'>European Commission Communications Networks, Content and Technology</a></span>";
        } else {
            $retval .= "&nbsp;";
        }

        $retval .= "
            </td>
        </tr>
    </table>
</div>";
        return $retval;
    }

    private function emptyImage($id, $alt) {
        $empty = $this->Gui->skinObject->findResourceUrl("IMAGES", "empty.png");
        $retval = '';
        if ($empty !== FALSE) {
            $retval = "<div>
    <img id='$id' src='$empty' alt='$alt'/>
 </div>";
        }
        return $retval;
    }

}
