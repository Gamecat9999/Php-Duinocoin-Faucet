<?php

final class LocalCaptcha
{
    private const LIFETIME = 300;

    public function __construct()
    {
        if (!$this->isValidChallenge()) {
            $this->createChallenge();
        }
    }

    public function getQuestion(): string
    {
        return $_SESSION['captcha_question'];
    }

    public function verify(string $answer): bool
    {
        $expected = $_SESSION['captcha_answer'] ?? null;
        $createdAt = $_SESSION['captcha_created_at'] ?? 0;
        unset($_SESSION['captcha_question'], $_SESSION['captcha_answer'], $_SESSION['captcha_created_at']);

        $isValid = is_string($expected)
            && time() - (int) $createdAt <= self::LIFETIME
            && hash_equals($expected, trim($answer));
        $this->createChallenge();

        return $isValid;
    }

    private function createChallenge(): void
    {
        $firstNumber = random_int(1, 9);
        $secondNumber = random_int(1, 9);
        $_SESSION['captcha_question'] = "What is $firstNumber + $secondNumber?";
        $_SESSION['captcha_answer'] = (string) ($firstNumber + $secondNumber);
        $_SESSION['captcha_created_at'] = time();
    }

    private function isValidChallenge(): bool
    {
        return isset(
            $_SESSION['captcha_question'],
            $_SESSION['captcha_answer'],
            $_SESSION['captcha_created_at']
        ) && time() - (int) $_SESSION['captcha_created_at'] <= self::LIFETIME;
    }
}
