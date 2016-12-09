<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/6/2016
 * Time: 6:32 PM
 */

namespace Webarq\Manager\Installer;


use Wa;
use Webarq\Info\TableInfo;

class CreateManager extends InstallerAbstract
{
    protected function installation(TableInfo $table)
    {
        if (null === array_get($this->payload, $table->getName() . '.create') && [] !== $table->getColumns()) {
            $code = $this->openClass($strClass = 'create_' . $table->getName() . '_class');
            $code .= $this->migrationUp($table);
// Separate method with new line
            $code .= PHP_EOL;
            $code .= $this->migrationDown($table);
            $code .= $this->closeClass();
// Copy migration file
//            $this->setMigrationFile($strClass, $code);
// Create model or not
            $this->createModelOrNot($table);
// Set payload
            array_set($this->payload, $table->getName() . '.create', $table->getSerialize());
        }
    }

    /**
     * Migration up method
     *
     * @param TableInfo $table
     * @return string
     */
    private function migrationUp(TableInfo $table)
    {
        //DDL script
        $str = '    /**' . PHP_EOL;
        $str .= '     * Run the migrations.' . PHP_EOL;
        $str .= '     *' . PHP_EOL;
        $str .= '     * @return void' . PHP_EOL;
        $str .= '     */' . PHP_EOL;
        $str .= '    public function up()' . PHP_EOL;
        $str .= '    {' . PHP_EOL;
        $str .= '        Schema::create(\'' . $table->getName() . '\', function(Blueprint $table)' . PHP_EOL;
        $str .= '        {' . PHP_EOL;

        foreach ($table->getColumns() as $column) {
            $str .= Wa::manager('installer.definition', $column)->getDefinition();
        }

        $str .= '        });' . PHP_EOL;
        return $str. '    }' . PHP_EOL;
    }

    /**
     * Migration  down method
     *
     * @param TableInfo $table
     * @return string
     */
    private function migrationDown(TableInfo $table)
    {
        $str = '    /**' . PHP_EOL;
        $str .= '     * Reverse the migrations.' . PHP_EOL;
        $str .= '     *' . PHP_EOL;
        $str .= '     * @return void' . PHP_EOL;
        $str .= '     */' . PHP_EOL;
        $str .= '    public function down()' . PHP_EOL;
        $str .= '    {' . PHP_EOL;
        $str .= '        Schema::drop(\'' . $table->getName() . '\');' . PHP_EOL;
        return $str . '    }' . PHP_EOL;
    }


    private function createModelOrNot(TableInfo $table)
    {
        $class = ucfirst(camel_case(str_singular($table->getName()))) . 'Model';
        if (!file_exists(__DIR__ . '/../../model/' . $class . '.php')
                && (null === ($config = $table->getExtra('create-model')) || true === $config)) {
            $str = '<?php' . PHP_EOL . PHP_EOL;
            $str .= 'namespace Webarq\Model;' . PHP_EOL . PHP_EOL . PHP_EOL;
            $str .= 'class ' . $class . ' extends AbstractListingModel' . PHP_EOL;
            $str .= '{' . PHP_EOL;
            $str .= '    protected $table = \'' . $table->getName() . '\';' . PHP_EOL;
            $str .= '}';

            $f = fopen(__DIR__ . '/../../model/' . $class . '.php', 'w+');
            fwrite($f, $str);
            fclose($f);
        }
    }
}