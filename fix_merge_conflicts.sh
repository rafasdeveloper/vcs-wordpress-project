#!/bin/bash

# Script to fix merge conflicts in WordPress repository
# This script removes merge conflict markers and keeps the appropriate code

echo "Starting to fix merge conflicts in WordPress repository..."

# Function to fix merge conflicts in a file
fix_merge_conflicts() {
    local file="$1"
    echo "Fixing merge conflicts in: $file"
    
    # Create a backup of the original file
    cp "$file" "$file.backup"
    
    # Remove merge conflict markers and keep the appropriate code
    # Pattern: <<<<<<< HEAD ... ======= ... >>>>>>> b1eea7a
    # We'll keep the code after ======= (the merged version)
    
    # Use sed to remove the conflict markers and keep the merged version
    sed -i '/^<<<<<<< HEAD$/,/^=======$/d' "$file"
    sed -i '/^>>>>>> b1eea7a/d' "$file"
    
    echo "Fixed: $file"
}

# Find all files with merge conflict markers
echo "Searching for files with merge conflicts..."

# Find files with <<<<<<< HEAD markers
conflicted_files=$(grep -l "<<<<<<< HEAD" -r wp-content/ 2>/dev/null || true)

if [ -z "$conflicted_files" ]; then
    echo "No files with merge conflicts found."
    exit 0
fi

echo "Found $(echo "$conflicted_files" | wc -l) files with merge conflicts:"
echo "$conflicted_files"

# Process each conflicted file
for file in $conflicted_files; do
    if [ -f "$file" ]; then
        fix_merge_conflicts "$file"
    fi
done

echo "Merge conflict fixing completed!"
echo "Backup files have been created with .backup extension"
echo "You can review the changes and remove .backup files if satisfied" 