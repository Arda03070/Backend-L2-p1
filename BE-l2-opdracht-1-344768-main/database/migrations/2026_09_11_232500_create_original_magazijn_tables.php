<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('Product')) {
            Schema::create('Product', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->string('Naam');
                $table->char('Barcode', 13);
                $this->systeemVelden($table);
            });
        }

        if (! Schema::hasTable('Allergeen')) {
            Schema::create('Allergeen', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->string('Naam');
                $table->string('Omschrijving');
                $this->systeemVelden($table);
            });
        }

        if (! Schema::hasTable('Leverancier')) {
            Schema::create('Leverancier', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->string('Naam');
                $table->string('ContactPersoon');
                $table->string('LeverancierNummer', 20);
                $table->string('Mobiel', 20);
                $this->systeemVelden($table);
            });
        }

        if (! Schema::hasTable('Magazijn')) {
            Schema::create('Magazijn', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->unsignedInteger('ProductId');
                $table->decimal('VerpakkingsEenheidInKilogram', 5, 2);
                $table->unsignedInteger('AantalAanwezig')->nullable();
                $this->systeemVelden($table);
                $table->foreign('ProductId', 'FK_Magazijn_Product')
                    ->references('Id')
                    ->on('Product');
            });
        }

        if (! Schema::hasTable('ProductPerAllergeen')) {
            Schema::create('ProductPerAllergeen', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->unsignedInteger('ProductId');
                $table->unsignedInteger('AllergeenId');
                $this->systeemVelden($table);
                $table->foreign('ProductId', 'FK_ProductPerAllergeen_Product')
                    ->references('Id')
                    ->on('Product');
                $table->foreign('AllergeenId', 'FK_ProductPerAllergeen_Allergeen')
                    ->references('Id')
                    ->on('Allergeen');
            });
        }

        if (! Schema::hasTable('ProductPerLeverancier')) {
            Schema::create('ProductPerLeverancier', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('Id');
                $table->unsignedInteger('LeverancierId');
                $table->unsignedInteger('ProductId');
                $table->date('DatumLevering');
                $table->unsignedInteger('Aantal');
                $table->date('DatumEerstVolgendeLevering')->nullable();
                $this->systeemVelden($table);
                $table->foreign('LeverancierId', 'FK_ProductPerLeverancier_Leverancier')
                    ->references('Id')
                    ->on('Leverancier');
                $table->foreign('ProductId', 'FK_ProductPerLeverancier_Product')
                    ->references('Id')
                    ->on('Product');
            });
        }
    }

    private function systeemVelden(Blueprint $table): void
    {
        $table->boolean('IsActief')->default(true);
        $table->string('Opmerking', 255)->nullable();
        $table->dateTime('DatumAangemaakt')->useCurrent();
        $table->dateTime('DatumGewijzigd')->useCurrent();
    }

    public function down(): void
    {
        // De oorspronkelijke magazijntabellen worden niet automatisch verwijderd.
    }
};
