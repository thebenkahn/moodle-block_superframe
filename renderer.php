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
 * superframe renderer 
 *
 * @package    block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * Renderer addedin MoodleBites for Developers Level 1 by students Benjamin Kahn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_superframe_renderer extends plugin_renderer_base {

    function display_view_page($url, $width, $height, $courseid, $blockid) {
        global $USER;
           
        $data = new stdClass();

        // User name (from an earlier optional exercise)
        $data->name = fullname($USER);

        // Page heading and iframe data.
        $data->heading = get_string('pluginname', 'block_superframe');
        $data->url = $url;
        $data->height = $height;
        $data->width = $width;
        $data->username = $username;
        
        // Create the return to course link
        $data->backtocourselink = new moodle_url('/course/view.php', ['id' => $courseid]);
        $data->backtocourselinktext = get_string('backtocourse', 'block_superframe');

        // Text for the links and the size parameter.
        $strings = array();
        $strings[] = get_string('custom', 'block_superframe');
        $strings[] = get_string('small', 'block_superframe');
        $strings[] = get_string('medium', 'block_superframe');
        $strings[] = get_string('large', 'block_superframe');

        // Create the data structure for the links.
        $links = array();
        $link = new moodle_url('/blocks/superframe/view.php', ['courseid' => $courseid,
                'blockid' => $blockid]);
        
        foreach ($strings as $string) {
            $links[] = ['link' => $link->out(false, ['size' => $string]),
                    'text' => $string];
        }

        $data->linkdata = $links;
   
           // Start output to browser.
           echo $this->output->header();
   
           // Render the data in a Mustache template.
           echo $this->render_from_template('block_superframe/frame', $data);
   
           // Finish the page.
           echo $this->output->footer();
       }

    public function fetch_block_content($blockid, $courseid) {
        global $USER;
        $data = new stdClass();
        $data->welcome =
                get_string('welcomeuser', 'block_superframe', $USER);

        $data->url = new moodle_url('/blocks/superframe/view.php', ['blockid' => $blockid,
            'courseid' => $courseid]);
        $data->text = get_string('viewlink', 'block_superframe');

        // Add a link to the popup page.
        $data->popurl = new moodle_url('/blocks/superframe/block_data.php');
        $data->poptext = get_string('poplink', 'block_superframe');

        // Add a link to the table manager page.
        $data->tableurl = new moodle_url('/blocks/superframe/tablemanager.php');
        $data->tabletext = get_string('tabletext', 'block_superframe');

        return $this->render_from_template('block_superframe/block_content',
                $data);
    }

    /**
     * Function to display a table of records
     * @param array the records to display.
     * @return none.
     */
    public function display_block_table($records) {
        // Prepare the data for the template.
        $table = new stdClass();
        // Table headers.
        $table->tableheaders = [
                get_string('blockid', 'block_superframe'),
                get_string('blockname', 'block_superframe'),
                get_string('course', 'block_superframe'),
                get_string('catname', 'block_superframe'),
        ];
        // Build the data rows.
        foreach ($records as $record) {
            $data = array();
            $data[] = $record->id;
            $data[] = $record->blockname;
            $data[] = $record->shortname;
            $data[] = $record->catname;
            $table->tabledata[] = $data;
        }
        // Start output to browser.
        echo $this->output->header();
        // Call our template to render the data.
        echo $this->render_from_template(
                'block_superframe/block_data', $table);
        // Finish the page.
        echo $this->output->footer();
    }

}