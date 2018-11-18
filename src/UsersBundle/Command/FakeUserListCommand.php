<?php

namespace UsersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UsersBundle\Controller\UserController;
use Symfony\Component\Console\Helper\Table;

class FakeUserListCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('random-user')
            ->setDescription('Sample User JSON List Returned')
            ->addArgument('f', InputArgument::OPTIONAL, 'return pretty table');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $input->getArgument('f');

        $fakeUserController = new UserController();

        $container = $this->getContainer();
        $fakeUserController->setContainer($container);
        $content = $fakeUserController->RandomUserListAction()->getContent();
        if (false === empty($content)) {
            if ($table) {
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
            } else {
                $output->writeln($content); // display all user from request in JSON format
                //$output->writeln(json_encode(json_decode($content)[0])); // display single/first user in JSON format
            }
        } else {
            $output->writeln('no content');
        }
    }
}
