#!/bin/bash

# HTML files optimization script for Core Web Vitals

FILES="services.html about.html contact.html"

for file in $FILES; do
    echo "Optimizing $file..."

    # Backup original file
    cp "$file" "$file.bak"

    # Remove fontawesome-init.js (already loaded in head)
    sed -i '' '/<script src="assets\/js\/fontawesome-init.js"><\/script>/d' "$file"

    # Add defer to JavaScript files
    sed -i '' 's/<script src="assets\/js\/nav.js"><\/script>/<script defer src="assets\/js\/nav.js"><\/script>/g' "$file"
    sed -i '' 's/<script src="assets\/js\/common.js"><\/script>/<script defer src="assets\/js\/common.js"><\/script>/g' "$file"
    sed -i '' 's/<script src="assets\/js\/external-links.js"><\/script>/<script defer src="assets\/js\/external-links.js"><\/script>/g' "$file"
    sed -i '' 's/<script src="assets\/js\/cookie-consent.js"><\/script>/<script defer src="assets\/js\/cookie-consent.js"><\/script>/g' "$file"
    sed -i '' 's/<script src="assets\/js\/form-validation.js"><\/script>/<script defer src="assets\/js\/form-validation.js"><\/script>/g' "$file"
    sed -i '' 's/<script src="assets\/js\/service-tabs.js"><\/script>/<script defer src="assets\/js\/service-tabs.js"><\/script>/g' "$file"

    echo "✓ $file optimized"
done

echo "All files optimized!"
