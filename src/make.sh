#!/bin/bash

echo ""
echo "Rebuilding couscous.phar from your current source code..."
bin/compile
echo "OK"
echo ""
echo "Copying couscous.phar to your working directory..."
cp bin/couscous.phar ../compiler/ -f
cp bin/couscous.version ../compiler/ -f
echo "OK"
echo ""

