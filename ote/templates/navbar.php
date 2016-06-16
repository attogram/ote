<?php // Open Translation Engine - navbar template v0.0.4

namespace Attogram;

?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php print $this->path; ?>/"><?php print $this->site_name; ?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse ">
      <ul class="nav navbar-nav">
        <li><a href="<?php print $this->path; ?>/search/"><span     class="icon-s">🔎</span><small> Search</small></a></li>
        <li><a href="<?php print $this->path; ?>/languages/"><span  class="icon-s">🌐</span><small> Languages</small></a></li>
        <li><a href="<?php print $this->path; ?>/dictionary/"><span class="icon-s">📚</span><small> Dictionary</small></a></li>
        <li><a href="<?php print $this->path; ?>/word/"><span       class="icon-s">🔤</span><small> Words</small></a></li>
        <li><a href="<?php print $this->path; ?>/export/"><span     class="icon-s">📤</span><small> Export</small></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php print $this->path; ?>/about/"><span   class="icon-s">💁</span><small> About OTE</small></a></li>
            <li><a href="<?php print $this->path; ?>/history/"><span class="icon-s">🔭</span><small> History</small></a></li>
            <li><a href="<?php print $this->path; ?>/tags/"><span    class="icon-s">⛓</span><small> Tags</small></a></li>
            <li><a href="https://github.com/attogram/ote"><span      class="icon-s">🐙</span><small> OTE @ GitHub</small></a></li>
            <li><a href="https://github.com/attogram/DAMS"><span     class="icon-s">➕</span><small> Open Content Dictionaries (DAMS)</small></a></li>
          </ul>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
<?php
  if( class_exists('\Attogram\attogram_user') ) {
    if( \Attogram\attogram_user::is_logged_in() ) {
      print '<li><a href="' . $this->path
      . '/user/"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <b>'
      . ( (isset($_SESSION['attogram_username']) && $_SESSION['attogram_username'])  ? $_SESSION['attogram_username'] : 'user')
      . '</b></a></li>';
      print '<li><a href="?logoff"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> logoff</a></li>';
    } else {
      if( array_key_exists('login', $this->get_actions()) ) { // if User Module is loaded
        print '<li><a href="' . $this->path
        . '/login/">login <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a></li>';
      }
    }
  } // end if user module active

  if( $this->is_admin() ) {
    print '<li class="dropdown">'
    . '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'
    . 'Admin <span class="caret"></span></a><ul class="dropdown-menu">';
    foreach( array_keys($this->get_admin_actions()) as $a ) {
      print '<li><a href="' . $this->path . '/' . $a . '/">' . $a . '</a></li>';
    }
    print '</ul></li>';
  }
  ?></ul>
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>