<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Map\Provider\GoogleMaps;

/**
 * Identifiers used to specify the placement of controls on the map.
 * Controls are positioned relative to other controls in the same layout position.
 * Controls that are added first are positioned closer to the edge of the map.
 * Usage of "logical values" (see https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_logical_properties_and_values) is recommended
 * in order to be able to automatically support both left-to-right (LTR) and right-to-left (RTL) layout contexts.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/control#ControlPosition
 */
enum ControlPosition: int
{
    /**
     * Equivalent to {@see self::BOTTOM_CENTER} in both LTR and RTL.
     */
    case BLOCK_END_INLINE_CENTER = 24;

    /**
     * Equivalent to {@see self::BOTTOM_RIGHT} in LTR, or {@see self::BOTTOM_LEFT} in RTL.
     */
    case BLOCK_END_INLINE_END = 25;

    /**
     * Equivalent to {@see self::BOTTOM_LEFT} in LTR, or {@see self::BOTTOM_RIGHT} in RTL.
     */
    case BLOCK_END_INLINE_START = 23;

    /**
     * Equivalent to {@see self::TOP_CENTER} in both LTR and RTL.
     */
    case BLOCK_START_INLINE_CENTER = 15;

    /**
     * Equivalent to {@see self::TOP_RIGHT} in LTR, or {@see self::TOP_LEFT} in RTL.
     */
    case BLOCK_START_INLINE_END = 16;

    /**
     * Equivalent to {@see self::TOP_LEFT} in LTR, or {@see self::TOP_RIGHT} in RTL.
     */
    case BLOCK_START_INLINE_START = 14;

    /**
     * Elements are positioned in the center of the bottom row. Consider using
     * {@see self::BLOCK_END_INLINE_CENTER} instead.
     */
    case BOTTOM_CENTER = 11;

    /**
     * Elements are positioned in the bottom left and flow towards the middle.
     * Elements are positioned to the right of the Google logo. Consider using
     * {@see self::BLOCK_END_INLINE_START} instead.
     */
    case BOTTOM_LEFT = 10;

    /**
     * Elements are positioned in the bottom right and flow towards the middle.
     * Elements are positioned to the left of the copyrights. Consider using
     * {@see self::BLOCK_END_INLINE_END} instead.
     */
    case BOTTOM_RIGHT = 12;

    /**
     * Equivalent to {@see self::RIGHT_CENTER} in LTR, or {@see self::LEFT_CENTER} in RTL.
     */
    case INLINE_END_BLOCK_CENTER = 21;

    /**
     * Equivalent to {@see self::RIGHT_BOTTOM} in LTR, or {@see self::LEFT_BOTTOM} in RTL.
     */
    case INLINE_END_BLOCK_END = 22;

    /**
     * Equivalent to {@see self::RIGHT_TOP} in LTR, or {@see self::LEFT_TOP} in RTL.
     */
    case INLINE_END_BLOCK_START = 20;

    /**
     * Equivalent to {@see self::LEFT_CENTER} in LTR, or {@see self::RIGHT_CENTER} in RTL.
     */
    case INLINE_START_BLOCK_CENTER = 17;

    /**
     * Equivalent to {@see self::LEFT_BOTTOM} in LTR, or {@see self::RIGHT_BOTTOM} in RTL.
     */
    case INLINE_START_BLOCK_END = 19;

    /**
     * Equivalent to {@see self::LEFT_TOP} in LTR, or {@see self::RIGHT_TOP} in RTL.
     */
    case INLINE_START_BLOCK_START = 18;

    /**
     * Elements are positioned on the left, above bottom-left elements, and flow
     * upwards. Consider using {@see self::INLINE_START_BLOCK_END} instead.
     */
    case LEFT_BOTTOM = 6;

    /**
     * Elements are positioned in the center of the left side. Consider using
     * {@see self::INLINE_START_BLOCK_CENTER} instead.
     */
    case LEFT_CENTER = 4;

    /**
     * Elements are positioned on the left, below top-left elements, and flow
     * downwards. Consider using {@see self::INLINE_START_BLOCK_START} instead.
     */
    case LEFT_TOP = 5;

    /**
     * Elements are positioned on the right, above bottom-right elements, and
     * flow upwards. Consider using {@see self::INLINE_END_BLOCK_END} instead.
     */
    case RIGHT_BOTTOM = 9;

    /**
     * Elements are positioned in the center of the right side. Consider using
     * {@see self::INLINE_END_BLOCK_CENTER} instead.
     */
    case RIGHT_CENTER = 8;

    /**
     * Elements are positioned on the right, below top-right elements, and flow
     * downwards. Consider using {@see self::INLINE_END_BLOCK_START} instead.
     */
    case RIGHT_TOP = 7;

    /**
     * Elements are positioned in the center of the top row. Consider using
     * {@see self::BLOCK_START_INLINE_CENTER} instead.
     */
    case TOP_CENTER = 2;

    /**
     * Elements are positioned in the top left and flow towards the middle.
     * Consider using {@see self::BLOCK_START_INLINE_START} instead.
     */
    case TOP_LEFT = 1;

    /**
     * Elements are positioned in the top right and flow towards the middle.
     * Consider using {@see self::BLOCK_START_INLINE_END} instead.
     */
    case TOP_RIGHT = 3;
}
