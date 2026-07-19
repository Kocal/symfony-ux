<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Toolkit\Tests\Kit\Lint\Checker;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Toolkit\Kit\Kit;
use Symfony\UX\Toolkit\Kit\KitManifest;
use Symfony\UX\Toolkit\Kit\Lint\Checker\DocHeadingLevelChecker;
use Symfony\UX\Toolkit\Kit\Lint\LintSeverity;
use Symfony\UX\Toolkit\Recipe\Recipe;
use Symfony\UX\Toolkit\Recipe\RecipeManifest;
use Symfony\UX\Toolkit\Recipe\RecipeType;

final class DocHeadingLevelCheckerTest extends TestCase
{
    public function testFlagsOnlyLevelOneHeadingsOutsideCodeFences()
    {
        $kit = new Kit(__DIR__, new KitManifest('kit', 'A kit', 'MIT', 'https://example.com'));
        $kit->addRecipe(new Recipe('ok', __DIR__, $this->recipeManifest('Ok'), doc: "## Usage\n\n### Basic\n\nSome text."));
        $kit->addRecipe(new Recipe('fenced', __DIR__, $this->recipeManifest('Fenced'), doc: "## Usage\n\n```md\n# not a heading\n```"));
        $kit->addRecipe(new Recipe('bad', __DIR__, $this->recipeManifest('Bad'), doc: "# Title\n\n## Usage"));

        $issues = iterator_to_array(new DocHeadingLevelChecker()->check($kit));

        $this->assertCount(1, $issues);
        $this->assertSame(LintSeverity::Error, $issues[0]->severity);
        $this->assertSame('doc.heading_level', $issues[0]->category);
        $this->assertSame('bad', $issues[0]->recipe);
        $this->assertStringContainsString('# Title', $issues[0]->message);
    }

    private function recipeManifest(string $name): RecipeManifest
    {
        return new RecipeManifest(RecipeType::Component, $name, []);
    }
}
