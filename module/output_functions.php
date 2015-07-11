<?php

/**
 * built_form_header()
 * returns the header line for a form
 * @param mixed $action
 * @return
 */
function built_form_header($action) {
    return '<form method="post" action="'.$action.'">' . "\n";
}

/**
 * built_form_footer()
 * returns the footer of aform
 * @param mixed $hidden
 * @return
 */
function built_form_footer($hidden) {
    $tabstring = '';

    foreach ($hidden as $hid) {
        $tabstring .= '<input type="hidden" name="'.$hid[0].'" value="'.$hid[1] .'" />' . "\n";
    }

    $tabstring .= '<form>' . "\n";

    return $tabstring;
}

/**
 * tablefooter_filter()
 * returns the foolter row for a table with filter button
 * @param mixed $cols
 * @param mixed $label
 * @return
 */
function tablefooter_filter($cols, $label) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="' . $cols . '"><input type="submit" name="filter" value="' . $label . '"></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    $tabstring .= '</table>' . "\n";

    return $tabstring;
}

/**
 * builddropdown()
 *
 * @param mixed $result     result of query
 * @param mixed $value      given value of the dropdown
 * @param mixed $valuecol   columnname of the query column that holds the value to be refferd to
 * @param mixed $displaycol columnname of the query column that holds the value to be displaed
 * @param integer $all      option to define if the default choice all should be given 0 - not dispaled, 1- displayed
 * @return
 */
function builddropdown($result, $value, $valuecol, $displaycol, $all=0) {
    $tabstring = '<select name="' . $valuecol . '">' . "\n";

    if ($all >0) {
        $tabstring .= sprintf('<option value="%d"%s>%s</option>',0, 0 == $value ? " selected" : "" ,_("All")) . "\n";
    }

    if(count($result) >= 1) {
        foreach($result as $row) {
             $tabstring .= sprintf('<option value="%d"%s>%s</option>',$row[$valuecol], $row[$valuecol] == $value ? " selected" : "" , $row[$displaycol]) . "\n";
        }
    }

    $tabstring .= '</select>' . "\n";

    return $tabstring;
}

/**
 * error()
 * returns the error output
 * @param mixed $output
 * @return
 */
function error($output) {
    $tabstring = '<div class="error_box">';
    $tabstring .= _('Error: ') . $output;
    $tabstring .= '</div>';

    output_debug_box($_SESSION ['debug']);

    return $tabstring;
}

/**
 * empty_line()
 * retrun an empty line
 * @return
 */
function empty_line() {
    return '<br/>' . "\n";
}

/**
 * tableheader()
 * returns a table header with colspan
 * @param mixed $title
 * @param mixed $cols
 * @return
 */
function tableheader($title,$cols) {
    $tabstring = '<table align="center" valign="middle" border="0" cellspacing="0" cellpadding="0" class="wrapper">' . "\n";
    $tabstring .= '<tr>' . "\n";
    $tabstring .= '    <td colspan="'.$cols.'" class="title">'.$title.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_2col_textbox()
 * return the 2 column table row wuth textbox
 * @param mixed $label
 * @param mixed $name
 * @param mixed $value
 * @return
 */
function tablerow_2col_textbox($label, $name, $value) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablecell()
 * returns a single table cell
 * @param mixed $value
 * @param integer $cols
 * @param string $align
 * @return
 */
function tablecell($value, $cols=0, $align='left') {
    $colspan = '';

    if ($cols > 0) {
        $colspan = ' colspan="' . $cols . '" ';
    }

    $tabstring =   '<td class="DataTD" ' . $colspan . ' align="' .$align. '">' . $value . '</td>' . "\n";

    return $tabstring;
}

/**
 * tablerow_start()
 * returns a table row start tag
 * @return
 */
function tablerow_start() {
    $tabstring = '<tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_end()
 * returns a table row end tag
 * @return
 */
function tablerow_end() {
    $tabstring = '</tr>' . "\n";

    return $tabstring;
}

/**
 * table_end()
 * returns a table end tag
 * @return
 */
function table_end() {
    $tabstring = '</table>' . "\n";

    return $tabstring;
}

/**
 * tablerow_label()
 * returns a table row with label and colspan
 * @param mixed $label
 * @param mixed $col
 * @return
 */
function tablerow_label($label, $col) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="'.$col.'">'.$label.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

/**
 * tablerow_3col_textbox_2col()
 * return as table row with 2 col where 1st col is label, 2nd col is colspan = 2
 * @param mixed $label
 * @param mixed $name
 * @param mixed $value
 * @return
 */
function tablerow_3col_textbox_2col($label, $name, $value) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="2"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

/**
 * tablerow_2col_dropbox()
 * returns a table row with dropbox
 * @param mixed $label
 * @param mixed $result
 * @param mixed $value
 * @param mixed $valuecol
 * @param mixed $displaycol
 * @param integer $all
 * @return
 */
function tablerow_2col_dropbox($label, $result, $value, $valuecol, $displaycol, $all=0) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . builddropdown($result, $value, $valuecol, $displaycol, $all) . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_2col_dropbox_apply()
 * returns a table row with dropbox and apply button
 * @param mixed $label
 * @param mixed $result
 * @param mixed $value
 * @param mixed $valuecol
 * @param mixed $displaycol
 * @param mixed $buttonname
 * @param mixed $buttonlabel
 * @param integer $all
 * @return
 */
function tablerow_2col_dropbox_apply($label, $result, $value, $valuecol, $displaycol, $buttonname, $buttonlabel, $all=0) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . builddropdown($result, $value, $valuecol, $displaycol, $all) . '<input type="submit" name="' . $buttonname . '" value="' . $buttonlabel . '"> </td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_user_rights()
 *  returns a table row with read and write permission checkboxes
 * @param mixed $roles
 * @param mixed $read
 * @param mixed $write
 * @return
 */
function tablerow_user_rights($roles, $read, $write) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"></td>' . "\n";
    $tabstring .= '    <td class="DataTD">Read</td>' . "\n";
    $tabstring .= '    <td class="DataTD">Write</td>' . "\n";

    $i = 0;
    foreach ($roles as $role) {
        if ((pow(2, $i) & $read) == pow(2, $i)) {
            $readpos = 'checked';
        } else {
            $readpos = '';
        }

        if ((pow(2, $i) & $write) == pow(2, $i)) {
            $writepos = 'checked';
        } else {
            $writepos = '';
        }

        $tabstring .= '</tr>' . "\n";
        $tabstring .= '    <td class="DataTD">'.$role.'</td>' . "\n";
        $tabstring .= '    <td class="DataTD"><input type="checkbox" name="read'.$i.'" '.$readpos.'/></td>' . "\n";
        $tabstring .= '    <td class="DataTD"><input type="checkbox" name="write'.$i.'" '.$writepos.'/></td>' . "\n";
        $tabstring .= '</tr>' . "\n";

        $i++;
    }

    return $tabstring;
}

/**
 * tablerow_2col_checkbox()
 * returns a table row with checkbox
 * @param mixed $label
 * @param mixed $name
 * @param mixed $value
 * @return
 */
function tablerow_2col_checkbox($label, $name, $value) {
    if ($value == 1) {
        $checked = 'checked';
    } else {
        $checked = '';
    }

    $tabstring = '</tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . $label . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><input type="checkbox" name="' .$name .'" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablefooter_user()
 * returns a table footer row this action button
 * @param mixed $cols
 * @param mixed $uid
 * @param integer $write
 * @return
 */
function tablefooter_user($cols, $uid, $write=0) {
    if ($uid == 0 ) {
        $label = _('New entry');
        $name = 'new';
    } else {
        $label = _('Save entry');
        $name = 'edit';
    }

    if ($write == 2) {
        $label = _('Delete entry');
        $name = 'delete';
    }

    if ($write !=0) {
        $tabstring = '<tr>' . "\n";
        $tabstring .= '    <td class="DataTD" colspan="'.$cols.'"><input type="submit" name="'.$name.'" value="'.$label.'"></td>' . "\n";
        $tabstring .= '</tr>' . "\n";
        $tabstring .= '</table>' . "\n";
    } else {
        $tabstring = '</table>' . "\n";
    }

    return $tabstring;
}

/**
 * tablerow_userlist_header()
 * returns a table header row for userlist
 * @return
 */
function tablerow_userlist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Coauditor') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Read permission') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Write permission') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_userlist()
 * returns a table row for userlist
 * @param mixed $user
 * @return
 */
function tablerow_userlist($user) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=user&cid='.$user['coauditor_id'].'">'.$user['coauditor_name'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$user['read_permission'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$user['write_permission'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_userlist_new()
 * returns a table footer row for userlist with new button
 * @return
 */
function tablerow_userlist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="3"><a href="../index.php?type=user&cid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_no_entry()
 * returns a table  row for for a table with no entries
 * @param mixed $cols
 * @return
 */
function tablerow_no_entry($cols) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="'.$cols.'">No entry available</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * start_div()
 * returns a start div tag
 * @param string $class
 * @return
 */
function start_div($class='') {
    if ($class != '') {
        $class = 'class="'.$class.'"';
    }

    $tabstring = '<div '.$class.'>' . "\n";

    return $tabstring;
}

/**
 * end_div()
 * returns a end div tag
 * @return
 */
function end_div() {
    $tabstring = '</div>' . "\n";

    return $tabstring;
}

//topic

/**
 * tablerow_topicslist_header()
 * returns a table header row for topic list
 * @return
 */
function tablerow_topicslist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Topic') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Explaination') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_topicslist()
 * returns a table row for topics list
 * @param mixed $topic
 * @return
 */
function tablerow_topicslist($topic) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=topic&tid='.$topic['session_topic_id'].'">'.$topic['session_topic'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$topic['topic_explaination'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$topic['activ'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_topicslist_new()
 * returns a footer row with new button for topic list
 * @return
 */
function tablerow_topicslist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="3"><a href="../index.php?type=topic&tid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}
/**
 * tablerow_topics_active()
 * returns a table row with checkbox active
 * @param mixed $active
 * @return
 */
function tablerow_topics_active($active) {
    if ($active == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }

    $tabstring = '</tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><input type="checkbox" name="active" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_topics_checkbox()
 * returns a table row with checkbox for topics
 * @param mixed $checkboxname
 * @param mixed $checkboxlabel
 * @param mixed $value
 * @return
 */
function tablerow_topics_checkbox($checkboxname, $checkboxlabel, $value) {
    if ($value == 1) {
        $checked = 'checked';
    } else {
        $checked = '';
    }

    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . $checkboxlabel . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><input type="checkbox" name="'. $checkboxname . '" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

// session
/**
 * tablerow_sessionslist_header()
 * returns a tabler header row for session list
 * @return
 */
function tablerow_sessionslist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Session') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('From') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('To') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Default') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Define Topics') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_sessionslist()
 * returns a tbale row for session list
 * @param mixed $session
 * @return
 */
function tablerow_sessionslist($session) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=session&sid='.$session['session_id'].'">'.$session['session_name'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$session['from'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$session['to'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$session['default'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$session['active'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=sessiontopiclist&sid='.$session['session_id'].'">Topics</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_sessionslist_new()
 * returns a table footer row with new button
 * @return
 */
function tablerow_sessionslist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="6"><a href="../index.php?type=session&sid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

// sessiontopics
/**
 * tablerow_sessiontopiclist_header()
 * returns a table header row for sesion topics list
 * @return
 */
function tablerow_sessiontopiclist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Session') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Topic No') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Topic') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Default') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_sessiontopicsslist()
 *returns a table row for session topic list
 * @param mixed $sessiontopic result set of a query
 * @return
 */
function tablerow_sessiontopicsslist($sessiontopic) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$sessiontopic['session_name'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$sessiontopic['topic_no'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=sessiontopic&stid='.$sessiontopic['session_topics_id'].'">'.$sessiontopic['session_topic'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$sessiontopic['active'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_sessionstopicslist_new()
 * returns a table footer with new button
 * @return
 */
function tablerow_sessionstopicslist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="4"><a href="../index.php?type=sessiontopic&stid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

// view managemnt
/**
 * tablerow_viewlist_header()
 * returns a table header row for viewlist
 * @return
 */
function tablerow_viewlist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('View') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Read Permission') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Write Permission') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_viewlist()
 * returns a table row for viewlist
 * @param mixed $view
 * @return
 */
function tablerow_viewlist($view) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=view&vid='.$view['view_rigths_id'].'">'.$view['view_name'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$view['read_permission'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$view['write_permission'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$view['active'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_viewlist_new()
 * returns a table footer row for viewlist with new button
 * @return
 */
function tablerow_viewlist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="4"><a href="../index.php?type=view&vid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

// kpi management
/**
 * tablerow_kpilist_header()
 * returns a table header row for kpilist
 * @return
 */
function tablerow_kpilist_header() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Session name') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Session year') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Assurances') . '</td>' . "\n";
    $tabstring .= '    <td class="DataTD">' . _('Target [%]') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_kpilist()
 * returns a table row for kpilist
 * @param mixed $kpi
 * @return
 */
function tablerow_kpilist($kpi) {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD"><a href="../index.php?type=kpi&kid='.$kpi['coaudit_refdata_id'].'">'.$kpi['session_name'].'</a></td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$kpi['session_year'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$kpi['assurances'].'</td>' . "\n";
    $tabstring .= '    <td class="DataTD">'.$kpi['target'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * tablerow_kpilist_new()
 * returns a table footer row for kpilist with new button
 * @return
 */
function tablerow_kpilist_new() {
    $tabstring = '<tr>' . "\n";
    $tabstring .= '    <td class="DataTD" colspan="4"><a href="../index.php?type=kpi&kid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";

    return $tabstring;
}

/**
 * last_id_entered()
 * returns the output with the ID of the last result entered
 * @param mixed $id
 * @return
 */
function last_id_entered($id) {
    $tabstring = '<div class="last_id">' . _('Please note the id of the last entry made on your Co-Audit form:') . '<strong>' . $id . '</strong></div>';

    return $tabstring;
}
//debug output
/**
 * output_debug_box()
 * retruns the debug information if present
 * @param mixed $message
 * @return
 */
function output_debug_box($message) {
    $message = trim($message);

    if ($message != '') {
        echo '<div class="debug_box">';
        echo $message;
        echo '</div>';
    }
}
