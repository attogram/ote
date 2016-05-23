<?php
namespace Attogram;

/**
 * tabler - table with view of database table content, plus optional admin links
 */
function tabler(
  $attogram,
  $table,
  $name_singular,
  $name_plural,
  $public_link,
  $col,
  $sql,
  $admin_link,
  $admin_create=FALSE,
  $admin_edit=FALSE,
  $admin_delete=FALSE
) {

  $result = $attogram->sqlite_database->query($sql);

  print '<div class="container"><p>'
  . '<strong>' . count($result) . '</strong> <a href="">' . $name_plural . '</a>';

  if( $admin_create ) {
    print ' &nbsp; &nbsp; &nbsp; '
    . '<a href="' . $public_link . '"><span class="glyphicon glyphicon-user"></span> view</a>'
    . ' &nbsp; &nbsp; &nbsp; '
    . '<a target="_db" href="' . $admin_create . '"><span class="glyphicon glyphicon-plus"></span> '
    . 'new ' . $name_singular . '</a>';
  } else {
    if( $attogram->is_admin() ) {
      print ' &nbsp; &nbsp; &nbsp; '
      . '<a href="' . $admin_link . '"><span class="glyphicon glyphicon-wrench"></span> Admin</a>';
    }
  }

  print '<p>'
  . '<table class="table table-bordered table-hover table-condensed">'
  . '<colgroup>';

  foreach( $col as $c ) {
    print '<col class="' . $c['class'] . '">';
  }

  print '</colgroup><thead><tr>';

  foreach( $col as $c ) {
    print '<th>' . $c['title'] . '</th>';
  }
  if( $admin_create ) {
    print '<th><nobr><small>'
    . 'edit <span class="glyphicon glyphicon-wrench" title="edit"></span>'
    . ' &nbsp; '
    . '<span class="glyphicon glyphicon-remove-circle" title="delete"></span> delete'
    . '</small></nobr></th>';
  }
  print '</tr></thead><tbody>';

  foreach( $result as $row ) {
    print '<tr>';
    foreach( $col as $c ) {
      print '<td>' . htmlentities($row[ $c['key'] ]) . '</td>';
    }
    if( $admin_create ) {
      print '<td> &nbsp; &nbsp; '
      . '<a target="_db" href="' . $admin_edit . '[' . $row['id'] . ']">'
      . '<span class="glyphicon glyphicon-wrench" title="edit"></span></a>'
      . ' &nbsp; &nbsp; '
      . '<a target="_db" href="' . $admin_delete . '[' . $row['id'] . ']">'
      . '<span class="glyphicon glyphicon-remove-circle" title="delete"></span></a>'
      . '</td>'
      ;
    }
    print '</tr>';
  }
  print '</tbody></table></div>';
}