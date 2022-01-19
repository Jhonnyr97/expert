<?php
/**
 * Moodle_Custom_Design
 * Configurable Reports to filter by Tag
 */

require_once($CFG->dirroot.'/blocks/configurable_reports/plugin.class.php');

class plugin_tags extends plugin_base {

    public function init() {
        $this->form = false;
        $this->unique = true;
        $this->fullname = get_string('filtertags', 'block_configurable_reports');
        $this->reporttypes = array('sql');
    }

    public function summary($data) {
        return get_string('filtertags_summary', 'block_configurable_reports');
    }

    public function execute($finalelements, $data) {        
        
        $filtertag = optional_param('filter_tag', 0, PARAM_INT);

        if (!$filtertag) {
            return $finalelements;
        }

        if (preg_match("/%%FILTER_TAG:([^%]+)%%/i", $finalelements, $output)) {
            $replace = ' AND FIND_IN_SET('.$filtertag.', '.$output[1].') > 0';
            return str_replace('%%FILTER_TAG:'.$output[1].'%%', $replace, $finalelements);
        }
        
        return $finalelements;
        
    }

    public function print_filter(&$mform) {
        global $remotedb, $CFG;

        $filtertag = optional_param('filter_tag', 0, PARAM_INT);
        $reportclassname = 'report_'.$this->report->type;        
        $reportclass = new $reportclassname($this->report);

        $systemtags = $remotedb->get_records('tag');
        $tags = array();
        foreach ($systemtags as $tag) {
            $tags[$tag->id] = $tag->rawname;
        }

        if ($this->report->type != 'sql') {
            $components = cr_unserialize($this->report->components);
            $conditions = $components['conditions'];

            $taglist = $reportclass->elements_by_conditions($conditions);
        } else {
            $taglist = $tags;
        }

        $tagoptions = array();
        $tagoptions[0] = get_string('filter_all', 'block_configurable_reports');

        if (!empty($taglist)) {
            // Todo: check that keys of role array items are available.
            foreach ($taglist as $key => $tag) {
                $tagoptions[$key] = $tag;
            }
        }

        $mform->addElement('select', 'filter_tag', get_string('filtertag', 'block_configurable_reports'), $tagoptions);
        $mform->setType('filter_tag', PARAM_INT);
    }
}

/**
 * Moodle_Custom_Design
 */
