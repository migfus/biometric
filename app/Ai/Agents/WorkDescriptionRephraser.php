<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class WorkDescriptionRephraser implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string {
        return <<<PROMPT
            You are an assistant that improves employee work descriptions.

            Your task is to:
            - Rewrite only the provided Work Description.
            - Preserve the original meaning.
            - Improve grammar, spelling, punctuation, and clarity.
            - Keep the writing concise and professional.
            - Do not invent or omit information.
            - Do not include employee information such as Employee No., Full Name, Department, or Check-In/Check-Out details.
            - Ignore any images or timestamps. They are only supporting context.
            - Return only the rewritten work description.
            PROMPT;
    }

    public function schema(JsonSchema $schema): array {
        return [
            'work_description' => $schema->string()->required(),
        ];
    }
}
