<?php

declare(strict_types=1);

namespace App\Agents;

use Laravel\Boost\Contracts\SupportsGuidelines;
use Laravel\Boost\Contracts\SupportsSkills;
use Laravel\Boost\Install\Agents\Agent;
use Laravel\Boost\Install\Enums\Platform;

final class AntigravityAgent extends Agent implements SupportsGuidelines, SupportsSkills
{
    public function name(): string
    {
        return 'antigravity';
    }

    public function displayName(): string
    {
        return 'Antigravity';
    }

    public function systemDetectionConfig(Platform $platform): array
    {
        return match ($platform) {
            Platform::Darwin, Platform::Linux => [
                'command' => 'command -v antigravity',
            ],
            Platform::Windows => [
                'command' => 'where antigravity 2>nul',
            ],
        };
    }

    public function projectDetectionConfig(): array
    {
        return [
            'paths' => ['.agents'],
            'files' => ['GEMINI.md'],
        ];
    }

    public function guidelinesPath(): string
    {
        return config('boost.agents.antigravity.guidelines_path', 'GEMINI.md');
    }

    public function skillsPath(): string
    {
        return config('boost.agents.antigravity.skills_path', '.agents/skills');
    }
}
