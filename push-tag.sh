#!/usr/bin/env bash

if [ "$#" -ne 1 ]; then
    echo "Usage: $0 <level>"
    exit 1
fi

LEVEL="$1"
OLD_TAG=$(git describe --tags --abbrev=0)

MAJOR=$(echo $OLD_TAG | cut -d. -f1)
MINOR=$(echo $OLD_TAG | cut -d. -f2)
PATCH=$(echo $OLD_TAG | cut -d. -f3)

case $LEVEL in
    "major")
        NEW_MAJOR=$(($MAJOR + 1))
        NEW_TAG="${NEW_MAJOR}.0.0"
        ;;
    "minor")
        NEW_MINOR=$(($MINOR + 1))
        NEW_TAG="${MAJOR}.${NEW_MINOR}.0"
        ;;
    "patch")
        NEW_PATCH=$(($PATCH + 1))
        NEW_TAG="${MAJOR}.${MINOR}.${NEW_PATCH}"
        ;;
    *)
        echo "Invalid level. Supported levels are: major, minor, patch"
        exit 1
        ;;
esac

git tag $NEW_TAG
git push origin $NEW_TAG
git tag --delete $OLD_TAG
git push --delete origin $OLD_TAG

echo "Version updated successfully from $OLD_TAG to $NEW_TAG"
