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
 * User login event.
 *
 * @package    core_auth
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_auth\event;

defined('MOODLE_INTERNAL') || die();

/**
 * User login event class.
 *
 * @package    core_auth
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_loggedin extends \core\event\base {

    /**
     * Returns localised description of what happened.
     *
     * @return \lang_string.
     */
    public function get_description() {
        return new \lang_string('event_user_loggedin_desc', '', $this->get_username());
    }

    /**
     * Return legacy data for add_to_log().
     *
     * @return array
     */
    public function get_legacy_logdata() {
        return array(SITEID, 'user', 'login', "view.php?id=" . $this->data['objectid'] . "&course=".SITEID,
            $this->data['objectid'], 0, $this->data['objectid']);
    }

    /**
     * Return localised event name.
     *
     * @return \lang_string
     */
    public static function get_name() {
        return new \lang_string('event_user_loggedin');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/user/profile.php', array('id' => $this->data['objectid']));
    }

    /**
     * Return the username of the logged in user.
     *
     * @return string
     */
    public function get_username() {
        return $this->data['other']['username'];
    }

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->context = \context_system::instance();
        $this->data['crud'] = 'r';
        $this->data['level'] = 50;          // TODO MDL-37658.
        $this->data['objecttable'] = 'user';
    }

    /**
     * Custom validation.
     *
     * @throws coding_exception when validation does not pass.
     * @return void
     */
    protected function validate_data() {
        if (!isset($this->data['objectid'])) {
            throw new \coding_exception("objectid has to be specified.");
        } else if (!isset($this->data['other']['username'])) {
            throw new \coding_exception("other['username'] has to be specified.");
        }
    }

}