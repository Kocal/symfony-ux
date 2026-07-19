<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Toolkit\Kit\Lint\Checker;

use Symfony\UX\Toolkit\Kit\Kit;
use Symfony\UX\Toolkit\Kit\Lint\KitCheckerInterface;
use Symfony\UX\Toolkit\Kit\Lint\LintIssue;
use Symfony\UX\Toolkit\Kit\Lint\LintSeverity;

/**
 * A recipe's `doc.md` is injected under the sections the doc template already renders at level 2
 * (`## Installation`, `## API Reference`, ...), so its own headings must start at level 2 too.
 * A level-1 heading (`#`) would render as a page title in the middle of the document.
 *
 * @author Hugo Alliaume <hugo@alliau.me>
 *
 * @internal
 */
final class DocHeadingLevelChecker implements KitCheckerInterface
{
    public function check(Kit $kit): iterable
    {
        foreach ($kit->getRecipes() as $recipe) {
            if (null === $recipe->doc) {
                continue;
            }

            foreach ($this->levelOneHeadings($recipe->doc) as $heading) {
                yield new LintIssue(
                    severity: LintSeverity::Error,
                    category: 'doc.heading_level',
                    message: \sprintf('The "doc.md" heading "%s" must start at level 2 ("## "); its content is injected under the recipe\'s own sections.', $heading),
                    recipe: $recipe->name,
                );
            }
        }
    }

    /**
     * @return iterable<string> the level-1 headings found outside fenced code blocks
     */
    private function levelOneHeadings(string $doc): iterable
    {
        $insideFence = false;

        foreach (explode("\n", $doc) as $line) {
            if (preg_match('/^\s*```/', $line)) {
                $insideFence = !$insideFence;

                continue;
            }

            if (!$insideFence && preg_match('/^#(?:\s|$)/', $line)) {
                yield trim($line);
            }
        }
    }
}
