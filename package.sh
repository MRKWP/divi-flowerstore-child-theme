plugin_basename=$(basename $(pwd))
version=$1

#clean up
rm -rf /tmp/$plugin_basename;
rm /tmp/$plugin_basename-$1.zip;

cd ..;
cp -r $plugin_basename /tmp;

cd -;
cd /tmp;

zip -r9 $plugin_basename-$version.zip $plugin_basename -x *.git* -x *.sh -x *package.json* -x *package-lock.json* -x *.github* -x *node_modules* -x *grunt* -x *gulpfile.js* -x *styles*> /dev/null;
rm -rf /tmp/$plugin_basename;