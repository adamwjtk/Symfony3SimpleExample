<?php

namespace UsersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use UsersBundle\Controller\UserController;
use Symfony\Component\Console\Helper\Table;

class FakeUserListCommand extends ContainerAwareCommand
{
    use LockableTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('random-user')
            ->setDescription('Sample User JSON List Returned')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption('ftable', 'f', InputOption::VALUE_NONE, 'Display pretty table')
                    ]
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('command is running now!');
            return 0;
        }

        $displayTableOpt = $input->getOptions();
        $fakeUserController = new UserController();

        $container = $this->getContainer();
        $fakeUserController->setContainer($container);
        $content = $fakeUserController->RandomUserListAction()->getContent();

        if (false === empty($content)) {
            if ($displayTableOpt["ftable"] && json_decode($content)) {
                $table = new Table($output);
                $table
                    ->setHeaders(array('First Name', 'Last Name', 'Address'));
                $i = 0;
                foreach (json_decode($content) as $row) {
                    $table->setRow($i++,
                        array($row->LastName, $row->FirstName, \implode(',', (array)$row->Address))
                    );
                }

                $table->render();
            } elseif (false === json_decode($content)) {
                $output->writeln('Table data problem! Try without --ftable option');
            } else {
                $output->writeln($content);
            }
        } else {
            $output->writeln('no content');
        }
        return 0;
    }
}
