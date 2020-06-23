<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait HasMedia {

	/*
	 * HasMedia Trait can be used by any model that wants to store multiple files of different type
	 * It is different from HasPhoto and Photo as it allows any type of file and not
	 * images:jpg,png, zip, pdf,
	 * Provides the polymorphic relationship medias() method
	 * And some helper functions to store and delete media
	 */

	// polymorphic relationship with App\Media get media related to  Model
	public function medias() {
		return $this->morphMany('App\Media', 'mediaable');
	}

	public function storeUploadedMedias($medias, $folderName = 'medias') {

		foreach ($medias as $media) {

			$title = $media->getClientOriginalName();

			$title = $this->getMediaTitle($title);

			$url = $media->store($folderName, 'public');

			$fileName = $title;
			$mimeType = $media->getClientMimeType(); //->getMimeType()
			$collectionName = $folderName;

	        // Add new record in media
			$this->medias()->create([
				'title' => $title,
				'url' => $url,
				'file_name' => $filename,
				'mimeType' => $mimeType,
				'collection_name' => $collectionName,
			]);
		}

		return true;
	}

	public function deleteUploadedMediaExceptPassedMediaNames($items=[]) {

		// [2] Without Observer mass delete unwanted medias with a single query
		// $medias = $this->medias()->whereNotIn('medias.title', $items)->get();
		// // delete the unwanted medias records form db in a single query which is better but Observers wont work here
		// $this->medias()->whereNotIn('medias.title', $items)->delete();
		// // then delete the medias file from storage
		// foreach($medias as $photo) {
		// 	Storage::disk('public')->delete($photo->url);
		// }
		// Going with option 2 for better performance.

		$items = $items ? $items : []; // set $items to empty array if null value passed

		$medias = $this->medias()->whereNotIn('medias.title', $items)->get();

		// delete the unwanted medias records form db in a single query which is better but Observers wont work here
	    $this->medias()->whereNotIn('medias.title', $items)->delete();

	    // then delete the medias file from storage
		foreach($medias as $media) {
			Storage::disk('public')->delete($media->url);
		}

		return true;
	}

	/*
	 * Method to get title from users original file name
	 * Override in Model if u want different implementation
     */
	public function getMediaTitle($originalNameWithExtension) {
		// make title unique using time() and rand()
		$filename = pathinfo($originalNameWithExtension, PATHINFO_FILENAME);
		$filename = (strlen($filename) > 10) ? substr($filename, 0, 10) : $filename; // only take 10 chars from filename
		$extension = pathinfo($originalNameWithExtension, PATHINFO_EXTENSION);
		return $filename . time() . rand(10, 999) . '.' . $extension;
	}


	/**
	 * The "booted" method of the model.
	 *
	 * @return void
	 */
	protected static function booted() {

		// On Model Delete Event
		static::deleted(function($model) {
			// delete related Photo records + files as well
			$medias = $model->medias()->get();
			// delete medias records
			$model->medias()->delete();
			// then delete the medias file from storage
			foreach($medias as $media) {
				Storage::disk('public')->delete($media->url);
			}
		});
	}
}