<?php

/**
 * @package block_unifiedcloset
 * @version 2016011900
 * @author Kroton Moodle Team <suporte_moodle@kroton.com.br>
 * @copyright (c) 2016, Kroton Educacional (http://www.kroton.com.br)
 */
require_once('../../../../../config.php');
require_once($CFG->dirroot . '/blocks/unifiedcloset/locallib.php');
require_once($CFG->dirroot . '/lib/filelib.php');

global $COURSE;
//Obrigatorio estar logado
require_login();
$context = context_course::instance($COURSE->id);

//Configuracoes de layout
$PAGE->set_context($context);
$PAGE->set_pagelayout('embedded');
$PAGE->set_url('/blocks/unifiedcloset/form.php');
$PAGE->set_title(get_string('unifiedcloset', 'block_unifiedcloset'));
$PAGE->set_heading(get_string('unifiedcloset', 'block_unifiedcloset'));
$PAGE->set_cacheable(true);

// Breadcrumb
$dirid = optional_param('dirid', 0, PARAM_INT);
$username = optional_param('username', null, PARAM_TEXT);

if($username) {
	$PAGE->navbar->add(get_string('pluginname', 'block_unifiedcloset'), new moodle_url('/blocks/unifiedcloset/view.php'));
	if($dirid) {
		$PAGE->navbar->add($username, new moodle_url('/blocks/unifiedcloset/view.php?username='.$username));
		breadcrumb($dirid, $username);
	} else {
		$PAGE->navbar->add($username);
	}
} else {
	$PAGE->navbar->add(get_string('pluginname', 'block_unifiedcloset'));
}

echo html_writer::empty_tag('input', array('type' => 'hidden', 'id' => 'wwwroot', 'name' => 'wwwroot', 'value' => $CFG->wwwroot));
echo html_writer::empty_tag('link', array('href'=>"https://fonts.googleapis.com/icon?family=Material+Icons",'rel'=>'stylesheet', 'type'=>'text/css'));
echo html_writer::tag('script', null, array('src'=>"https://code.jquery.com/jquery-1.12.3.min.js", 'type'=>'text/javascript'));
echo html_writer::empty_tag('link', array('href'=>"{$CFG->wwwroot}/blocks/unifiedcloset/style.css",'rel'=>'stylesheet', 'type'=>'text/css'));
echo html_writer::tag('script', null, array('src'=>"{$CFG->wwwroot}/blocks/unifiedcloset/js/form.js",'type'=>'text/javascript'));
echo html_writer::empty_tag('link', array('href'=>'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css','rel'=>'stylesheet', 'type'=>'text/css'));
echo html_writer::empty_tag('link', array('href'=>'https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css','rel'=>'stylesheet', 'type'=>'text/css'));

echo html_writer::tag('script', null, array('src'=>'https://cdn.datatables.net/v/dt/dt-1.10.12/r-2.1.0/datatables.min.js','type'=>'text/javascript'));
echo html_writer::tag('script', null, array('src'=>'https://cdn.datatables.net/responsive/1.0.0/js/dataTables.responsive.min.js','type'=>'text/javascript'));
echo html_writer::tag('script', null, array('src'=>"{$CFG->wwwroot}/blocks/unifiedcloset/js/grid.js",'type'=>'text/javascript'));
echo html_writer::tag('script', null, array('src'=> get_texteditor('tinymce')->get_tinymce_base_url()."tiny_mce_popup.js",'type'=>'text/javascript'));

echo html_writer::start_tag('body');
//Box Master: Inicio
echo html_writer::start_div('block_unifiedcloset_master');

//Grid
// TODO Adaptar para o file picker
echo html_writer::div($block_unifiedcloset_grid->display_tinymce($dirid, $username), 'block_unifiedcloset_grid');

//Box Master: Fim
echo html_writer::end_div();

echo html_writer::end_tag('body');

//Limpa a sessao
unset($_SESSION['block']['unifiedcloset']['post']);

function breadcrumb($dirid, $username) {
	global $DB, $PAGE;
	$page_dir = optional_param('dirid', null, PARAM_INT);

	$dir = $DB->get_record('unifiedcloset_directory', array('id' => $dirid));
	if($dir->parent) {
		breadcrumb($dir->parent, $username);
		if($dirid === $page_dir) {
			$PAGE->navbar->add($dir->name);
		} else {
			$PAGE->navbar->add($dir->name, new moodle_url('/blocks/unifiedcloset/view.php?dirid='.$dirid.'&username='.$username));
		}
	} else {
		if($dirid === $page_dir) {
			$PAGE->navbar->add($dir->name);
		} else {
			$PAGE->navbar->add($dir->name, new moodle_url('/blocks/unifiedcloset/view.php?dirid='.$dirid.'&username='.$username));
		}
	}

}
