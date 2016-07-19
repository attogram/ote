<?php // Open Translation Engine - Import Page v0.3.2

namespace Attogram;

//ob_end_clean(); // turn off attogram ob_gzhandler // errors on php 5.3.3

$this->pageHeader('📥 Import Translations');

$default['s'] = '';
$default['t'] = '';
$default['d'] = '=';

if ($_POST) {
    if (!isset($_POST['w']) || !$_POST['w']) {
      $error[] = 'error - no word pairs found';
    }
    if (!isset($_POST['d']) || !$_POST['d']) {
      $error[] = 'error - no deliminator set';
    }
    if (!isset($_POST['t']) || !$_POST['t']) {
      $error[] = 'error - no target language code set';
    }
    if (!isset($_POST['s']) || !$_POST['s']) {
      $error[] = 'error - no source language code set';
    }
    if (isset($error)) {
      print '<div class="container"><p>' . implode($error, '<br />' ) . '</p></div>';
    } else {
      $ote = new OpenTranslationEngine($this);
      $ote->doImport($_POST['w'], $_POST['d'], $_POST['s'], $_POST['t'], @$_POST['sn'], @$_POST['tn'] );
    }
}
?>
<div class="container">
  <h1 class="squished">📥 Import Translations</h1>
  <form action="." method="POST">
<div class="row">
  <div class="col-xs-4">
    <label for="s">Source Language Code</label>
    <br />
    <input type="text" class="form-control" name="s" value="<?php print $default['s']; ?>">
  </div>
  <div class="col-xs-4">
    <label for="d">Deliminator</label>
    <br />
    <input type="text" class="form-control" name="d" value="<?php print $default['d']; ?>">
  </div>
  <div class="col-xs-4">
    <label for="t">Target Language Code</label>
    <br />
    <input type="text" class="form-control" name="t" value="<?php print $default['t']; ?>">
  </div>
</div>

 <div class="row">
  <div class="col-xs-12"><br /></div>
 </div>

<div class="row">
  <div class="col-xs-6">
    <label for="sn">Source Language Name</label>
    <br />
    <input type="text" class="form-control" name="sn" value="">
  </div>
  <div class="col-xs-6">
    <label for="tn">Target Language Name</label>
    <br />
    <input type="text" class="form-control" name="tn" value="">
  </div>
 </div>

 <div class="row">
  <div class="col-xs-12"><br /></div>
 </div>

 <div class="row">
  <div class="col-xs-12">
    <label for="w">Word pairs:</label>
    <textarea class="form-control" name="w" rows="8"></textarea>
    <button type="submit" class="btn btn-primary btn-block">
     <h4>
       <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
       &nbsp; Import Translations
     </h4>
    </button>
  </div>
 </div>

  </form>
</div>
<?php
$this->pageFooter();
