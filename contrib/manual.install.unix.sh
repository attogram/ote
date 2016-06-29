echo "Open Translation Engine - Manual Install Script for unix platforms - v0.0.2"

INSTALL_DIR="./ote-test" # no trailing slash
INSTALL_CACHE="./install-cache" # no trailing slash

ATTOGRAM_ZIP="https://github.com/attogram/attogram/archive/master.zip"
ATTOGRAM_FILE="attogram.zip"
ATTOGRAM_DIR="attogram-master" # no trailing slash

ATTOGRAM_VENDOR_ZIP="https://github.com/attogram/attogram-vendor/archive/master.zip"
ATTOGRAM_VENDOR_FILE="attogram_vendor.zip"
ATTOGRAM_VENDOR_DIR="attogram-vendor-master" # no trailing slash

OTE_ZIP="https://github.com/attogram/ote/archive/master.zip"
OTE_FILE="ote.zip"
OTE_DIR="ote-master" # no trailing slash

UNZIP="unzip" # -q
WGET="wget" # --no-verbose
CP="cp --recursive --verbose" # --verbose

echo ----------------------
if [ ! -d "$INSTALL_CACHE" ];
then
  echo "Creating install cache: $INSTALL_CACHE"
  mkdir $INSTALL_CACHE
fi
echo "Install cache: $INSTALL_CACHE"
if [ ! -d "$INSTALL_DIR" ];
then
  echo "Creating install directory: $INSTALL_DIR"
  mkdir $INSTALL_DIR
fi
echo "Install directory: $INSTALL_DIR"

echo ----------------------
echo "Get Attogram Framework"
if [ -r "$INSTALL_CACHE/$ATTOGRAM_FILE" ];
then
  echo "OK: $INSTALL_CACHE/$ATTOGRAM_FILE"
else
  $WGET --output-document=$INSTALL_CACHE/$ATTOGRAM_FILE $ATTOGRAM_ZIP
fi
echo "Get Attogram Framework Vendor package"
if [ -r "$INSTALL_CACHE/$ATTOGRAM_VENDOR_FILE" ];
then
  echo "OK: $INSTALL_CACHE/$ATTOGRAM_VENDOR_FILE"
else
  $WGET --output-document=$INSTALL_CACHE/$ATTOGRAM_VENDOR_FILE $ATTOGRAM_VENDOR_ZIP
fi
echo "Get Open Translation Engine"
if [ -r "$INSTALL_CACHE/$OTE_FILE" ];
then
  echo "OK: $INSTALL_CACHE/$OTE_FILE"
else
  $WGET --output-document=$INSTALL_CACHE/$OTE_FILE $OTE_ZIP
fi

echo ----------------------
echo "Unzip Attogram Framework"
if [ -d "$INSTALL_CACHE/$ATTOGRAM_DIR" ];
then
  echo "OK: $INSTALL_CACHE/$ATTOGRAM_DIR"
else
  $UNZIP -d $INSTALL_CACHE $INSTALL_CACHE/$ATTOGRAM_FILE
fi
echo "Unzip Attogram Framework vendor"
if [ -d "$INSTALL_CACHE/$ATTOGRAM_VENDOR_DIR" ];
then
  echo "OK: $INSTALL_CACHE/$ATTOGRAM_VENDOR_DIR"
else
  $UNZIP -d $INSTALL_CACHE $INSTALL_CACHE/$ATTOGRAM_VENDOR_FILE
fi
echo "Unzip Open Translation Engine"
if [ -d "$INSTALL_CACHE/$OTE_DIR" ];
then
  echo "OK: $INSTALL_CACHE/$OTE_DIR"
else
  $UNZIP -d $INSTALL_CACHE $INSTALL_CACHE/$OTE_FILE
fi


echo ----------------------
echo "Install Attogram Framework Vendor package"
$CP $INSTALL_CACHE/$ATTOGRAM_VENDOR_DIR/vendor $INSTALL_CACHE/$ATTOGRAM_DIR/vendor
echo "Installing Open Translation Engine module"
$CP $INSTALL_CACHE/$OTE_DIR $INSTALL_CACHE/$ATTOGRAM_DIR/modules/ote
echo "Creating Attogram config"
$CP $INSTALL_CACHE/$ATTOGRAM_DIR/public/config.sample.php $INSTALL_CACHE/$ATTOGRAM_DIR/public/config.php
echo "Making database directory writeable"
chmod 777 $INSTALL_CACHE/$ATTOGRAM_DIR/db

echo ----------------------
echo "Creating:"
$CP $INSTALL_CACHE/$ATTOGRAM_DIR $INSTALL_DIR

echo ----------------------
echo "Initialization done."
echo "Attogram Install Directory: $INSTALL_DIR"
echo "Attogram Config: $INSTALL_DIR/public/config.php"
echo "Open Translation Engine module: $INSTALL_DIR/modules/ote/"
echo
echo "TODO: set web server home as: $INSTALL_DIR/public"
echo "TODO: edit $INSTALL_DIR/public/.htaccess"
echo "TODO: edit $INSTALL_DIR/public/config.php"
echo

echo ----------------------
echo "END"
echo
