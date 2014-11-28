<?php

function built_form_header($action){
    return '<form method="post" action="'.$action.'">' . "\n";
}

function built_form_footer($hidden){
    $tabstring = '';
    foreach ($hidden as $hid) {
        $tabstring .= '<input type="hidden" name="'.$hid[0].'" value="'.$hid[1] .'" />';
    }
    $tabstring .= '<form>' . "\n";
    return $tabstring;

}


function tablefooter_filter($cols, $label){
        $tabstring = '<tr>' . "\n";
        $tabstring .=    '<td class="DataTD" colspan="' . $cols . '"><input type="submit" name="filter" value="' . $label . '"</td>' . "\n";
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
function builddropdown($result, $value, $valuecol, $displaycol, $all=0){
    $tabstring = '<select name="' . $valuecol . '">' . "\n";
    if ($all >0) {
        $tabstring .= sprintf('<option value="%d"%s>%s</option>',0, 0 == $value ? " selected" : "" ,_("All")) . "\n";
    }
    if(count($result) >= 1){
        foreach($result as $row){
             $tabstring .= sprintf('<option value="%d"%s>%s</option>',$row[$valuecol], $row[$valuecol] == $value ? " selected" : "" , $row[$displaycol]) . "\n";
        }
    }
    $tabstring .= '</select>' . "\n";
    return $tabstring;
}


function error($output){
    $tabstring = '<div class="error_box">';
    $tabstring .= _('Error: ') . $output;
    $tabstring .= '</div>';
    output_debug_box($_SESSION ['debug']);
    return $tabstring;
}

function empty_line(){
    return '<br/>' . "\n";
}

function tableheader($title,$cols){
    $tabstring = '<table align="center" valign="middle" border="0" cellspacing="0" cellpadding="0" class="wrapper">' . "\n";
    $tabstring .= '<tr>' . "\n";
    $tabstring .=   '<td colspan="'.$cols.'" class="title">'.$title.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;

}

function tablerow_2col_textbox($label, $name, $value){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablecell($value, $cols=0){
    $colspan = '';
    if ($cols > 0) {
        $colspan = ' colspan="' . $cols . '" ';
    }
    $tabstring =   '<td class="DataTD" ' . $colspan . '>' . $value . '</td>' . "\n";
    return $tabstring;
}
function tablerow_start(){
    $tabstring = '<tr>' . "\n";
    return $tabstring;
}
function tablerow_end(){
    $tabstring = '</tr>' . "\n";
    return $tabstring;
}
function table_end(){
    $tabstring = '</table>' . "\n";
    return $tabstring;
}

function tablerow_label($label, $col){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="'.$col.'">'.$label.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_3col_textbox_2col($label, $name, $value){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="2"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_2col_dropbox($label, $result, $value, $valuecol, $displaycol, $all=0){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . builddropdown($result, $value, $valuecol, $displaycol, $all) . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_2col_dropbox_apply($label, $result, $value, $valuecol, $displaycol, $buttonname, $buttonlabel, $all=0){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . builddropdown($result, $value, $valuecol, $displaycol, $all) . '<input type="submit" name="' . $buttonname . '" value="' . $buttonlabel . '"> </td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}
function tablerow_user_rights($roles, $read, $write){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"></td>' . "\n";
    $tabstring .=   '<td class="DataTD">Read</td>' . "\n";
    $tabstring .=   '<td class="DataTD">Write</td>' . "\n";
    $i = 0;
    foreach ($roles as $role) {
        if ((pow(2, $i) & $read) == pow(2, $i)) {
            $readpos = 'checked';
        }else{
            $readpos = '';
        }

        if ((pow(2, $i) & $write) == pow(2, $i)) {
            $writepos = 'checked';
        }else{
            $writepos = '';
        }

        $tabstring .= '</tr>' . "\n";
        $tabstring .=   '<td class="DataTD">'.$role.'</td>' . "\n";
        $tabstring .=   '<td class="DataTD"><input type="checkbox" name="read'.$i.'" '.$readpos.'/></td>' . "\n";
        $tabstring .=   '<td class="DataTD"><input type="checkbox" name="write'.$i.'" '.$writepos.'/></td>' . "\n";
        $tabstring .= '</tr>' . "\n";

        $i +=1;
    }

    return $tabstring;

}


function tablerow_2col_checkbox($label, $name, $value){
    if ($value == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    $tabstring = '</tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . $label . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input type="checkbox" name="' .$name .'" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablefooter_user($cols, $uid, $write=0){
    if ($uid == 0 ) {
        $label = 'New entry';
        $name = 'new';
    }else{
        $label = 'Save entry';
        $name = 'edit';
    }
    if ($write !=0) {
        $tabstring = '<tr>' . "\n";
        $tabstring .=    '<td class="DataTD" colspan="'.$cols.'"><input type="submit" name="'.$name.'" value="'.$label.'"</td>' . "\n";
        $tabstring .= '</tr>' . "\n";
        $tabstring .= '</table>' . "\n";
        return $tabstring;
    } else {
        return '';
    }

}

function tablerow_userlist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Coauditor') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Read permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Write permission') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_userlist($user){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=user&cid='.$user['coauditor_id'].'">'.$user['coauditor_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$user['read_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$user['write_permission'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_userlist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="3"><a href="../index.php?type=user&cid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_no_entry($cols){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="'.$cols.'">No entry available</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function start_div($class=''){
    if ($class != '') {
        $class = 'class="'.$class.'"';
    }
    $tabstring = '<div '.$class.'>' . "\n";
    return $tabstring;
}

function end_div(){
    $tabstring = '</div>' . "\n";
    return $tabstring;
}

//topic

function tablerow_topicslist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Topic') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Explaination') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_topicslist($topic){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=topic&tid='.$topic['session_topic_id'].'">'.$topic['session_topic'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$topic['topic_explaination'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$topic['activ'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_topicslist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="3"><a href="../index.php?type=topic&tid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}
function tablerow_topics_active($active){
    if ($active == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    $tabstring = '</tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input type="checkbox" name="active" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;

}
function tablerow_topics_checkbox($checkboxname, $checkboxlabel, $value){
    if ($value == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    $tabstring = '</tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . $checkboxlabel . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input type="checkbox" name="'. $checkboxname . '" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;

}

// session
function tablerow_sessionslist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Session') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('From') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('To') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Default') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Define Topics') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_sessionslist($session){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=session&sid='.$session['session_id'].'">'.$session['session_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$session['from'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$session['to'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$session['default'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$session['active'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=sessiontopiclist&sid='.$session['session_id'].'">Topics</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_sessionslist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="6"><a href="../index.php?type=session&sid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

// sessiontopics
function tablerow_sessiontopiclist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Session') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Topic No') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Topic') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Default') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

/**
 * tablerow_sessiontopicsslist()
 *
 * @param mixed $sessiontopic result set of a query
 * @return
 */
function tablerow_sessiontopicsslist($sessiontopic){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$sessiontopic['session_name'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$sessiontopic['topic_no'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=sessiontopic&stid='.$sessiontopic['session_topics_id'].'">'.$sessiontopic['session_topic'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$sessiontopic['active'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


/**
 * tablerow_sessionstopicslist_new()
 *
 * @return
 */
function tablerow_sessionstopicslist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="4"><a href="../index.php?type=sessiontopic&stid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

// view managemnt
function tablerow_viewlist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('View') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Read Permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Write Permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_viewlist($view){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=view&vid='.$view['view_rigths_id'].'">'.$view['view_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['read_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['write_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['active'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_viewlist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="4"><a href="../index.php?type=view&vid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

// kpi managemnt
function tablerow_kpilist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Session name') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Session year') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Assurances') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Target [%]') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_kpilist($kpi){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../index.php?type=kpi&kid='.$kpi['coaudit_refdata_id'].'">'.$kpi['session_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$kpi['session_year'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$kpi['assurances'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$kpi['target'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_kpilist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="4"><a href="../index.php?type=kpi&kid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


//debug output
/**
 * output_debug_box()
 * retruns the debug information if present
 * @param mixed $message
 * @return
 */
function output_debug_box($message){
    $message = trim($message);
    if ($message != '') {
        echo '<div class="debug_box">';
        echo $message;
        echo '</div>';
   }
}
?>