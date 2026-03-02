#!/bin/bash

# Script to fix remaining merge conflicts in WordPress repository
# This script removes orphaned merge conflict markers

echo "Starting to fix remaining merge conflicts in WordPress repository..."

# Function to fix orphaned merge conflict markers in a file
fix_orphaned_markers() {
    local file="$1"
    echo "Fixing orphaned markers in: $file"
    
    # Create a backup of the original file
    cp "$file" "$file.backup2"
    
    # Remove orphaned >>>>>>> b1eea7a markers
    sed -i '/^>>>>>>> b1eea7a (Merged existing code from https:\/\/dev-vices\.rafaeldeveloper\.co)$/d' "$file"
    
    echo "Fixed: $file"
}

# Find all files with orphaned merge conflict markers
echo "Searching for files with orphaned merge conflict markers..."

# Find files with >>>>>>> b1eea7a markers (excluding backup files and script files)
conflicted_files=$(grep -l ">>>>>>> b1eea7a" -r wp-content/ --exclude="*.backup*" --exclude="*.sh" 2>/dev/null || true)

if [ -z "$conflicted_files" ]; then
    echo "No files with orphaned merge conflict markers found."
    exit 0
fi

echo "Found $(echo "$conflicted_files" | wc -l) files with orphaned merge conflict markers:"
echo "$conflicted_files"

# Process each conflicted file
for file in $conflicted_files; do
    if [ -f "$file" ]; then
        fix_orphaned_markers "$file"
    fi
done

echo "Orphaned merge conflict marker fixing completed!"
echo "Backup files have been created with .backup2 extension"
echo "You can review the changes and remove .backup2 files if satisfied" 