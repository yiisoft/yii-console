<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @codeCoverageIgnore
 */
#[AsCommand('|yii', 'A Guessing Game')]
final class Game extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $steps = 1;
        $number = random_int(0, 100);

        $io = new SymfonyStyle($input, $output);
        $io->title('Welcome to the Guessing Game!');


        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question('Please enter a number between 0 and 100: ');

        while (true) {
            $answer = (int) $helper->ask($input, $output, $question);

            if ($answer === $number) {
                $io->success('You win! You guessed the number in ' . $steps . ' steps.');
                return 0;
            }

            $steps++;
            if ($answer > $number) {
                $io->warning('Too high!');
            } else {
                $io->warning('Too low!');
            }
        }
    }
}
