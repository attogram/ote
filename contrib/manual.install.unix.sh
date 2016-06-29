echo "Open Translation Engine - Manual Install Script for unix platforms - v0.0.1"
echo ----------------------

INSTALL_DIR="./ote-test"
ATTOGRAM_ZIP="https://github.com/attogram/attogram/archive/master.zip"
ATTOGRAM_FILE="attogram.zip"
ATTOGRAM_DIR="attogram-master"
ATTOGRAM_VENDOR_ZIP="https://github.com/attogram/attogram-vendor/archive/master.zip"
ATTOGRAM_VENDOR_FILE="attogram_vendor.zip"
ATTOGRAM_VENDOR_DIR="attogram-vendor-master"
OTE_ZIP="https://github.com/attogram/ote/archive/master.zip"
OTE_FILE="ote.zip"
OTE_DIR="ote-master"
UNZIP="unzip -q"
WGET="wget --no-verbose"
CP="cp --recursive" # --verbose

echo "Setup:"
echo "INSTALL_DIR: " $INSTALL_DIR
echo "Attogram Framework: zip: $ATTOGRAM_ZIP"
echo "Attogram Framework: file: $ATTOGRAM_FILE"
echo "Attogram Framework: dir: $ATTOGRAM_DIR"
echo "Attogram Framework vendor: zip: $ATTOGRAM_VENDOR_ZIP"
echo "Attogram Framework vendor: file: $ATTOGRAM_VENDOR_FILE"
echo "Attogram Framework vendor: dir: $ATTOGRAM_VENDOR_DIR"
echo "Open Translation Engine: zip: $OTE_ZIP"
echo "Open Translation Engine: file: $OTE_FILE"
echo "Open Translation Engine: dir: $OTE_DIR"
echo "wget: $WGET"
echo "unzip: $UNZIP"
echo "cp: $CP"

echo ----------------------
echo "Get Attogram Framework"
if [ -r "$ATTOGRAM_FILE" ];
then
  echo "OK: $ATTOGRAM_FILE"
else
  $WGET --output-document=$ATTOGRAM_FILE $ATTOGRAM_ZIP
fi
echo "Get Attogram Framework Vendor package"
if [ -r "$ATTOGRAM_VENDOR_FILE" ];
then
  echo "OK: $ATTOGRAM_VENDOR_FILE"
else
  $WGET --output-document=$ATTOGRAM_VENDOR_FILE $ATTOGRAM_VENDOR_ZIP
fi
echo "Get Open Translation Engine"
if [ -r "$OTE_FILE" ];
then
  echo "OK: $OTE_FILE"
else
  $WGET --output-document=$OTE_FILE $OTE_ZIP
fi

echo ----------------------
echo "Unzip Attogram Framework"
if [ -d "$ATTOGRAM_DIR" ];
then
  echo "OK: $ATTOGRAM_DIR"
else
  $UNZIP $ATTOGRAM_FILE
fi
echo "Unzip Attogram Framework vendor"
if [ -d "$ATTOGRAM_VENDOR_DIR" ];
then
  echo "OK: $ATTOGRAM_VENDOR_DIR"
else
  $UNZIP $ATTOGRAM_VENDOR_FILE
fi
echo "Unzip Open Translation Engine"
if [ -d "$OTE_DIR" ];
then
  echo "OK: $OTE_DIR"
else
  $UNZIP $OTE_FILE
fi

echo ----------------------
echo "Install Attogram Framework Vendor package"
$CP $ATTOGRAM_VENDOR_DIR/vendor $ATTOGRAM_DIR/vendor
echo "Installing Open Translation Engine module"
$CP $OTE_DIR $ATTOGRAM_DIR/modules/ote
echo "Creating Attogram config"
$CP $ATTOGRAM_DIR/public/config.sample.php $ATTOGRAM_DIR/public/config.php
echo "Making database directory writeable"
chmod 777 $ATTOGRAM_DIR/db

echo ----------------------
echo "Attogram Install Directory: ./$ATTOGRAM_DIR/"
echo "Attogram Config: ./$ATTOGRAM_DIR/public/config.php"
echo "Open Translation Engine module: ./$ATTOGRAM_DIR/modules/ote/"
echo
echo "TODO: edit ./$ATTOGRAM_DIR/public/.htaccess"
echo "TODO: edit ./$ATTOGRAM_DIR/public/config.php"
echo

echo ----------------------
echo "END"
echo
