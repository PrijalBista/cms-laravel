<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait HasPhoto {

	/*
	 * HasPhoto Trait can be used by any model that wants to store multiple photos
	 * Provides the polymorphic relationship photos() method
	 * And some helper functions to store and delete images/photos
	 */

	// polymorphic relationship with App\Photo get photos related to Post Model and certain post id.
	public function photos() {
		return $this->morphMany('App\Photo', 'photoable');
	}

	public function storeUploadedImages($images, $folderName = 'images', $category) {

		foreach ($images as $image) {

			$title = $image->getClientOriginalName();

			$title = $this->getImageTitle($title);

			$url = $image->store($folderName, 'public');

	        // Add new record in photos
			$this->photos()->create([
				'title' => $title,
				'url' => $url,
				'category' => $category,
			]);
		}

		return true;
	}

	public function deleteUploadedImagesExceptPassedImageNames($items=[]) {

	    // NOTE observers doesn't work for mass delete :( So model needs to be loaded and deleted one by one.
	    // TWO APPROACHES
	    // [1] Use Observer But multiple delete queries needed
	    // $photos = $this->photos()->whereNotIn('photos.title', $items)->get();
	    // foreach($photos as $photo) {
	    // 	$photo->delete(); // Multiple queries needed . Observer works though
	    // }
	    //
		// [2] Without Observer mass delete unwanted photos with a single query
		// $photos = $this->photos()->whereNotIn('photos.title', $items)->get();
		// // delete the unwanted photos records form db in a single query which is better but Observers wont work here
		// $this->photos()->whereNotIn('photos.title', $items)->delete();
		// // then delete the photos file from storage
		// foreach($photos as $photo) {
		// 	Storage::disk('public')->delete($photo->url);
		// }
		// Going with option 2 for better performance.

		$items = $items ? $items : []; // set $items to empty array if null value passed

		$photos = $this->photos()->whereNotIn('photos.title', $items)->get();

		// delete the unwanted photos records form db in a single query which is better but Observers wont work here
	    $this->photos()->whereNotIn('photos.title', $items)->delete();

	    // then delete the photos file from storage
		foreach($photos as $photo) {
			Storage::disk('public')->delete($photo->url);
		}

		return true;
	}

	// public function deleteAllUploadedPhotos() {
	// 	// delete related Photo records + files
	// 	$photos = $this->photos()->get();
	// 	// delete photos records
	// 	$this->photos()->delete();
	// 	// then delete the photos file from storage
	// 	foreach($photos as $photo) {
	// 		Storage::disk('public')->delete($photo->url);
	// 	}
	// 	return true;
	// }

	/*
	 * Method to get title from users original file name
	 * Override in Model if u want different implementation
     */
	public function getImageTitle($originalNameWithExtension) {
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
			$photos = $model->photos()->get();
			// delete photos records
			$model->photos()->delete();
			// then delete the photos file from storage
			foreach($photos as $photo) {
				Storage::disk('public')->delete($photo->url);
			}
		});
	}
}