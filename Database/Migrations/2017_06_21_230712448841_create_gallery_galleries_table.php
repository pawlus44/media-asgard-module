<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryGalleriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gallery__galleries', function(Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
            $table->integer('status')->default(1);
			$table->integer('type');

            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});

		Schema::create('gallery__gallery_translations', function(Blueprint $table) {
			$table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('gallery_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['gallery_id', 'locale']);
            $table->foreign('gallery_id')->references('id')->on('gallery__galleries')->onDelete('cascade');

            $table->string('title');
            $table->string('slug');

            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});


		Schema::create('gallery__media_files', function(Blueprint $table) {
			$table->engine = 'InnoDB';

            $table->integer('gallery_id')->unsigned();
            $table->foreign('gallery_id')->references('id')->on('gallery__galleries')->onDelete('cascade');

            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('media__files')->onDelete('cascade');

            $table->integer('order');

            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gallery__media_files');
		Schema::drop('gallery__gallery_translations');
		Schema::drop('gallery__galleries');
	}
}
