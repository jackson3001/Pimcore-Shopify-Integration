<?php
namespace ShopifyBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateClassCommand extends Command
{
    protected static $defaultName = 'shopify-bundle:generate-class';

    protected function configure()
    {
        $this
            ->setDescription('Generate Configuration in ShopifyBundle')
            ->setHelp('This command generates ShopifyStore class in ShopifyBundle.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classPath = 'bundles/ShopifyBundle/storage/definition_ShopifyStore.php';
        $classDestinationPath = 'var/classes/definition_ShopifyStore.php';

        // Check if the class already exists
        if (file_exists($classDestinationPath)) {
            $output->writeln('The class already exists.');
            return Command::SUCCESS;
        }

        // Copy the template file to create the class
        $filesystem = new Filesystem();
        if (file_exists($classPath)) {
            $filesystem->copy($classPath, $classDestinationPath);
            // $filesystem->remove('bundles/ShopifyBundle/storage');
        }

        $output->writeln('ShopifyStore class has been generated.');

        return Command::SUCCESS;
    }
}
