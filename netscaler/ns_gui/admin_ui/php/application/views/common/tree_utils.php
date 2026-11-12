<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
//Class used to print tree of custom/built-in reports
class tree_utils
{
    //When a node is being created, add it to the tree under its parent
    public $is_current_node_new = false;
    public $parent_of_current_node = null;

    //Used to create a separate tree without leaves
    public $create_separate_tree_with_nodes = false;
    public $id_used_for_separate_tree = "";
    public $id_used_for_no_nodes = "";
    public $message_used_for_no_nodes = "";
    public $separate_tree_with_nodes_inner_html = "";

    //Id prefix of each node
    public $id_prefix = "";
    public $table_class_name = "";
    public $padding_left = 2;
    public $target_for_links = "content_iframe";
    public $select_leaf_onclick = true;
    public $ds_url = null;
    public $controller_name = "repcontent";
    public $print_check_box = false;
    public $expand_all_nodes = false;
    public $check_box_index = 0;

    //Used in recursion
    public $depth = 1;
    public $id_of_node_to_expand = "";
    public $found_current_node = false;

    private function print_tree_recursively($selected_node_name, $nodes, $ds = null, $depth = 1)
    {
        $selected_node_name= htmlentities($selected_node_name);
        foreach($nodes as $node)
        {
            $node_name = $node["name"];
            $node_name= htmlentities($node_name);
            $node_key = isset($node["key"]) ? $node["key"] : $node["name"];
            if(!isset($node["nodes"])) //leaf node
            {
                print "\n<tr><td id=\"" . $depth . "_lvl\" class=\"tree_link\">";
                $class_for_div = "";
                $id_for_div = "";
                if($selected_node_name == $node_key)
                {
                    $this->found_current_node = true;
                    $class_for_div = "class=\"tree_link_selected\"";
                    $id_for_div = "id=\"selected_tree_node_div\"";
                }

                $width_div_nodes = (200 - (10*($depth-2))) ."px";
                $style_width = "width:" .$width_div_nodes;
                print "<div $id_for_div $class_for_div"  . (($depth >= 2) ? ("style=" .$style_width. "") : "") ." >";
                if($this->print_check_box)
                    print "<input type=\"checkbox\" id=\"" . $this->check_box_index++ . "_ckb\" value=\"$node_key\" onclick=\"row_selected(event, this)\">";
                print "&nbsp;<a href=\"/menu/" . $this->controller_name . "?name=" . urlencode(preg_replace("/\//", "%2F", $node_key)) . $this->ds_url . "\" " . $this->target_for_links . " title= \"" . $node_name  . "\"". $this->select_leaf_onclick . ">" . $node_name ."</a>";
                print "</div>";
                print "</td></tr>";
            }
            else //Sub node
            {
                if($this->create_separate_tree_with_nodes)
                    $this->separate_tree_with_nodes_inner_html .= "<tr><td class=\"no_wrap_td\" onclick=\"javascript:select_tree_node('" . $node_name ."')\"><img src=\"/admin_ui/common/images/folder.png\" alt=\"Folder\" title=\"\" border=\"0\">" . $node_name . "</td></tr><tr><td style=\"padding-left: " .  $this->padding_left . "px\"><table id='" . $this->id_prefix . $node_name . "_nodes_table' class='". $this->table_class_name ."'>";
                print "\n<tr><td class=\"tree_sub_header\" title=\"Click to expand/collapse this folder\" onclick=\"javascript:show_hide_tree_node('" . $this->id_prefix . $node_key ."')\">";
                if($this->print_check_box)
                    print "<input type=\"checkbox\" id=\"" . $this->check_box_index++ . "_ckb\" value=\"$node_key\" onclick=\"row_selected(event, this)\" title=\"\">";
                else
                    print "<img id=\"" . $this->id_prefix . $node_key ."_img\"  src=\"/admin_ui/common/images/TreeCollapsed.png\" alt=\"Folder\" title=\"\" border=\"0\">";
                $node_display_style = $this->expand_all_nodes ? "" : "style=\"display: none\"";
                print "&nbsp;" . $node_name . "</td></tr><tr id=\"" . $this->id_prefix . $node_key ."\" $node_display_style><td style=\"padding-left: " . $this->padding_left . "px\"><table id=\"" . $this->id_prefix . $node_key . "_table\" class='". $this->table_class_name ."'>";
                $prev_found = $this->found_current_node;
                $this->depth++;
                $this->print_tree_recursively($selected_node_name, $node["nodes"], $ds, $this->depth);
                $this->depth--;
                //Add new node under its parent
                if($this->is_current_node_new && $this->parent_of_current_node == $node_key)
                {
                    $this->found_current_node = true;
                    print "\n<tr><td id=\"leaf_node_sub_header\" class=\"tree_link\">";
                    print "<div id=\"selected_tree_node_div\" class=\"tree_link_selected\">&nbsp;<a href=\"/menu/" . $this->controller_name . "?name=" . urlencode(preg_replace("/\//", "%2F", $selected_node_name)) . $this->ds_url . "\" " . $this->target_for_links . " title=" . $node_name . $this->select_leaf_onclick . ">" . $selected_node_name ."</a></div></td></tr>";                    
                }
                if($this->found_current_node && $prev_found != $this->found_current_node)
                {
                    if(strlen($this->id_of_node_to_expand) > 0)
                        $this->id_of_node_to_expand .= ",";
                    $this->id_of_node_to_expand .= $this->id_prefix . $node_key;
                }
                if($this->create_separate_tree_with_nodes)
                    $this->separate_tree_with_nodes_inner_html .= "</table></td></tr>";
                print "</table></td></tr>";
            }
        }
    }

    public function print_tree($selected_node_name, $nodes, $ds = null)
    {
        $selected_node_name = htmlentities($selected_node_name);
        $this->ds_url = ($ds != null) ? "&ds=$ds" : "";
        $this->target_for_links = ($this->target_for_links != null) ? "target=\"" . $this->target_for_links . "\"" : "";
        $this->select_leaf_onclick = $this->select_leaf_onclick ? " onclick=\"return select_tree_leaf(this);\"" : "";
        if($this->print_check_box)
            $this->padding_left = 5;
        if($this->create_separate_tree_with_nodes)
            $this->separate_tree_with_nodes_inner_html = "<table class='separate_tree_with_nodes_table'><tr><td class=\"no_wrap_td\" onclick=\"javascript:select_tree_node('" . $this->id_used_for_separate_tree ."')\"><img src=\"/admin_ui/common/images/folder.png\" alt=\"Folder\" title=\"\" border=\"0\">" . $this->id_used_for_separate_tree . "</td></tr><tr><td style=\"padding-left: " .  $this->padding_left . "px\"><table id='" . $this->id_prefix . $this->id_used_for_separate_tree . "_nodes_table'>";
        $this->print_tree_recursively($selected_node_name, $nodes, $ds, $this->depth);
        if($this->create_separate_tree_with_nodes)
            $this->separate_tree_with_nodes_inner_html .= "</table></td></tr></table>";
        if($this->is_current_node_new && !$this->found_current_node)
        {
            print "\n<tr><td class=\"tree_link\">";
            print "<div id=\"selected_tree_node_div\" class=\"tree_link_selected\">&nbsp;<a href=\"/menu/" . $this->controller_name . "?name=" . urlencode(preg_replace("/\//", "%2F", $selected_node_name)) . $this->ds_url . "\" " . $this->target_for_links . " title=\"Click to view/modify this report\"" . $this->select_leaf_onclick . ">" . $selected_node_name ."</a></div></td></tr>";
        }
        else if(count($nodes) == 0)
            print "\n<tr id=\"" . $this->id_used_for_no_nodes . "\"><td class=\"tree_link disabled_element\">" . $this->message_used_for_no_nodes . "</td></tr>";
    }
}
?>