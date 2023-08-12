#!/usr/bin/env bash
OLD_TAG=$(git describe --tags --abbrev=0)

MAJOR=$(echo $OLD_TAG | cut -d. -f1)
MINOR=$(echo $OLD_TAG | cut -d. -f2)
PATCH=$(echo $OLD_TAG | cut -d. -f3)

NEW_PATCH=$(($PATCH + 1))

NEW_TAG="${MAJOR}.${MINOR}.${NEW_PATCH}"

git tag $NEW_TAG
git push origin $NEW_TAG
git tag --delete $OLD_TAG
git push --delete origin $OLD_TAG