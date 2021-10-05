<?php
/**
 * Testimonial Form.
 *
 * @package mc-testimonial
 * @since   1.0.0
 * @author  Mafel John Cahucom
 */
?>

<style type="text/css">
	.mc-tst__row {
		margin-bottom: 20px;
	}
	.mc-tst__label {
		display: block;
		margin-bottom: 5px;
	    font-size: 13px;
	    font-weight: 500;
	}
	.mc-tst__red {
		color: #de4437;
	}

	.mc-tst__input,
	.mc-tst__textarea {
		display: block;
		padding: 8px 16px;
		width: 100%;
		height: 50px;
		color: #616a66;
		border: 1px solid #e6e6e6;
		outline: none;
	}
	.mc-tst__textarea {
		height: 100px;
	}


	.mc-tst__rating-list {
		display: flex;
		align-items: center;
		padding: 0;
		margin: 0;
		list-style: none;
	}
	.mc-tst__rating-star-btn {
		padding: 0;
		margin-right: 5px;
		background: transparent;
		border: 0;
		fill: gray;
		cursor: pointer;
	}
	.mc-tst__rating-star-btn[data-state="active"] {
		fill: #ffc313;
	}
	.mc-tst__rating-star-btn svg {
		display: block;
		width: 16px;
	}
	#mc-tst-image {
		display: none;
	}
	.mc-tst__label-upload {
		display: flex;
		align-items: center;
		padding: 8px 16px;
		width: 170px;
	    border: 1px solid #e6e6e6;
	    border-radius: 3px;
	 	font-weight: 400;
	    cursor: pointer;
	}
	.mc-tst__label-upload:hover {
		border-color: #212121;
		transition: all 320ms ease-in-out 0s;
	}
	.mc-tst__icon-upload {
		display: block;
		margin-right: 5px;
		width: 16px;
	}
	.mc-tst__action-btn[disabled] {
		cursor: wait;
	}
	#mc-tst-js-error-message {
		background: #de4437;
	}
	#mc-tst-js-success-message {
		background: #00d664;
	}
	.mc-tst__response-message {
		display: none;
		padding: 5px 10px;
		color: #ffffff;
		font-size: 14px;
		border-radius: 3px;
	}
	.mc-tst__response-message[data-state="visible"] {
		display: block;
	}
</style>

<form id="mc-tst-js-form" class="mc-tst__form" enctype="multipart/form-data" data-url="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>">
	<?php wp_nonce_field( 'mc_save_testimonial', 'mc_save_testimonial_nonce' ); ?>
	<input type="hidden" name="action" value="mc_save_testimonial">

	<div class="mc-tst__row">
		<label class="mc-tst__label">FULLNAME <span class="mc-tst__red">*</span></label>
		<input type="text" id="mc-tst-fullname" class="mc-tst__input" name="fullname" placeholder="Joe Doe">
	</div>

	<div class="mc-tst__row">
		<label class="mc-tst__label">POSITION <span class="mc-tst__red">*</span></label>
		<input type="text" id="mc-tst-position" class="mc-tst__input" name="position" placeholder="Engineer">
	</div>

	<div class="mc-tst__row">
		<label class="mc-tst__label">MESSAGE <span class="mc-tst__red">*</span></label>
		<textarea id="mc-tst-message" class="mc-tst__textarea" name="message" placeholder="Hi! Tell your experience with us..."></textarea>
	</div>

	<div class="mc-tst__row">
		<label class="mc-tst__label">RATING <span class="mc-tst__red">*</span></label>
		<?php
			$mc_star = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M394 480a16 16 0 01-9.39-3L256 383.76 127.39 477a16 16 0 01-24.55-18.08L153 310.35 23 221.2a16 16 0 019-29.2h160.38l48.4-148.95a16 16 0 0130.44 0l48.4 149H480a16 16 0 019.05 29.2L359 310.35l50.13 148.53A16 16 0 01394 480z"/></svg>';
		?>
		<ul class="mc-tst__rating-list">
			<li>
				<button type="button" class="mc-tst-js-rating-star-btn mc-tst__rating-star-btn" data-rating="1" data-state="unactive">
					<?php echo $mc_star; ?>
				</button>
			</li>
			<li>
				<button type="button" class="mc-tst-js-rating-star-btn mc-tst__rating-star-btn" data-rating="2" data-state="unactive">
					<?php echo $mc_star; ?>
				</button>
			</li>
			<li>
				<button type="button" class="mc-tst-js-rating-star-btn mc-tst__rating-star-btn" data-rating="3" data-state="unactive">
					<?php echo $mc_star; ?>
				</button>
			</li>
			<li>
				<button type="button" class="mc-tst-js-rating-star-btn mc-tst__rating-star-btn" data-rating="4" data-state="unactive">
					<?php echo $mc_star; ?>
				</button>
			</li>
			<li>
				<button type="button" class="mc-tst-js-rating-star-btn mc-tst__rating-star-btn" data-rating="5" data-state="unactive">
					<?php echo $mc_star; ?>
				</button>
			</li>
		</ul>
		<input type="hidden" id="mc-tst-rating" name="rating" value="">
	</div>

	<div class="mc-tst__row">
		<label for="mc-tst-image" class="mc-tst__label-upload">
			<svg xmlns="http://www.w3.org/2000/svg" class="mc-tst__icon-upload" viewBox="0 0 512 512"><path d="M416 64H96a64.07 64.07 0 00-64 64v256a64.07 64.07 0 0064 64h320a64.07 64.07 0 0064-64V128a64.07 64.07 0 00-64-64zm-80 64a48 48 0 11-48 48 48.05 48.05 0 0148-48zM96 416a32 32 0 01-32-32v-67.63l94.84-84.3a48.06 48.06 0 0165.8 1.9l64.95 64.81L172.37 416zm352-32a32 32 0 01-32 32H217.63l121.42-121.42a47.72 47.72 0 0161.64-.16L448 333.84z"/></svg>
			<span id="mc-tst-js-label-upload">Upload Profile</span>
		</label>
		<input type="file" id="mc-tst-image" accept="image/png, image/jpeg">
	</div>

	<div class="mc-tst__row">
		<div id="mc-tst-js-error-message" class="mc-tst__response-message" data-state="hidden"></div>
		<div id="mc-tst-js-success-message" class="mc-tst__response-message" data-state="hidden"></div>
	</div>

	<div class="mc-tst__row">
		<button type="button" id="mc-tst-js-clear-btn" class="mc-tst__action-btn">Clear</button>
		<button type="submit" id="mc-tst-js-submit-btn" class="mc-tst__action-btn">Submit</button>
	</div>

</form>

<script type="text/javascript">
	const mctst = {

		/**
		 * Initialize.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			this.updateRatingValueEvent();
			this.renameUploadLabelEvent();
			this.submitFormEvent();
			this.clearFormEvent();
		},

		/**
		 * Updates the value of the rating input based
		 * on the selected star.
		 *
		 * @since 1.0.0
		 */
		updateRatingValueEvent: function() {
			const ratingStarBtnElems = document.querySelectorAll('.mc-tst-js-rating-star-btn'),
				  ratingInputElem 	 = document.getElementById('mc-tst-rating');
			if ( ratingStarBtnElems.length === 0 || ! ratingInputElem ) return;

			ratingStarBtnElems.forEach( function( ratingStarBtnElem ) {
				ratingStarBtnElem.addEventListener( 'click', function( e ) {
					const target = e.target,
						  rating = target.getAttribute('data-rating');
					if ( rating < 1 && rating > 5 ) return;

					// Reset stars.
					mctst.resetRatingStarsEvent();

					// Set state to active.
					ratingStarBtnElems.forEach( function( starBtnElem, index ) {
						if ( index <= ( rating - 1 ) ) {
							starBtnElem.setAttribute( 'data-state', 'active' );
						}
					});

					// Set the value.
					ratingInputElem.value = rating;
 				});
			});
		},

		/**
		 * Reset the rating star to default.
		 *
		 * @since 1.0.0
		 */
		resetRatingStarsEvent: function() {
			const ratingStarBtnElems = document.querySelectorAll('.mc-tst-js-rating-star-btn');
			if ( ratingStarBtnElems === 0 ) return;

			ratingStarBtnElems.forEach( function( ratingStarBtnElem ) {
				ratingStarBtnElem.setAttribute( 'data-state', 'unactive' );
			});
		},

		/**
		 * Rename the upload label after selecting
		 * successfully and image.
		 *
		 * @since 1.0.0
		 */
		renameUploadLabelEvent: function() {
			const uploadElem = document.getElementById('mc-tst-image');
			if ( ! uploadElem ) return;

			uploadElem.addEventListener( 'change', function( e ) {
				const target = e.target,
					  imageFile = target.files[0].name.split('.'),
					  imageFilename = imageFile[0],
					  imageExtension = imageFile[ imageFile.length -1 ];

				let filename = target.files[0].name;
				if ( imageFilename.length > 10 ) {
					filename = `${ imageFilename.substring( 0, 10 ) }...${ imageExtension }`;
				}
				
				const uploadLabelElem = document.getElementById('mc-tst-js-label-upload');
				if ( uploadLabelElem ) {
					uploadLabelElem.textContent = filename;
				}
			});
		},

		/**
		 * Submit the testimonial form.
		 *
		 * @since 1.0.0
		 */
		submitFormEvent: function() {
			const formElem = document.getElementById('mc-tst-js-form');
			if ( ! formElem ) return;

			formElem.addEventListener( 'submit', function( e ) {
				e.preventDefault();

				const target   = e.target,
					  url 	   = target.getAttribute('data-url'),
					  fullname = document.getElementById('mc-tst-fullname'),
					  position = document.getElementById('mc-tst-position'),
					  message  = document.getElementById('mc-tst-message'),
					  rating   = document.getElementById('mc-tst-rating'),
					  image    = document.getElementById('mc-tst-image');


				if ( url === '' ) {
					mctst.showResponseMsg(
						'Missing important attribute please try to refresh.',
						'error'
					);
					return;
				}

				if ( fullname.value === '' ) {
					mctst.showResponseMsg( 'Fullname is required.', 'error' );
					return;
				}

				if ( position.value === '' ) {
					mctst.showResponseMsg( 'Position is required.', 'error' );
					return;
				}

				if ( message.value === '' ) {
					mctst.showResponseMsg( 'Message is required.', 'error' );
					return;
				}

				const ratingValue = parseInt( rating.value );
				if ( rating.value ) {
					if ( isNaN( ratingValue ) || ratingValue < 0 || ratingValue > 5 ) {
						mctst.showResponseMsg( 'Rating invalid value.', 'error' );
						return;
					}
				} else {
					mctst.showResponseMsg( 'Rating is required.', 'error' );
					return;
				}

				if ( image.files.length > 0 ) {
					if ( image.files[0].type != 'image/jpeg' && image.files[0].type != 'image/png' ) {
						mctst.showResponseMsg(
							'Invalid image extension jpg/png are allowed.',
							'error'
						);
						return;
					}

					if( image.files[0].size > 50000 ) {
						mctst.showResponseMsg(
							'Image filesize exceed, allowed only 50kb.',
							'error'
						);
						return;
					}
				}

				// Disable all action.
				mctst.setActionState( 'disable' );

				// AJAX Request.
				const formData = new FormData( this );
				formData.append('image_file', image.files[0]);

				const xhr = new XMLHttpRequest();
				xhr.open( 'POST', url, true );
				xhr.onload = function() {
					if ( this.readyState == 4 && this.status == 200 ) {
						const results = JSON.parse( this.response );
						if ( results.success == true ) {
							mctst.showResponseMsg( results.data.message, 'success' );
							mctst.resetFormEvent();
						} else {
							const errorMessage = results.data.errors[0];
							mctst.showResponseMsg( errorMessage, 'error' );
						}
						// Enable all action.
						mctst.setActionState( 'enable' );
					}
				}
				xhr.send( formData );
			});
		},

		/**
		 * Clear the testimonial form.
		 *
		 * @since 1.0.0
		 */
		clearFormEvent: function() {
			const clearBtnElem = document.getElementById('mc-tst-js-clear-btn');
			if ( ! clearBtnElem ) return;

			clearBtnElem.addEventListener( 'click', function() {
				mctst.resetFormEvent();
				mctst.hideResponseMsg();
			});
		},

		/**
		 * Reset the testimonial form or
		 * reset to the default.
		 *
		 * @since 1.0.0
		 */
		resetFormEvent: function() {
			const image      = document.getElementById('mc-tst-image'),
				  rating     = document.getElementById('mc-tst-rating'),
				  message  	 = document.getElementById('mc-tst-message'),
				  fullname   = document.getElementById('mc-tst-fullname'),
				  position   = document.getElementById('mc-tst-position'),
				  imageLabel = document.getElementById('mc-tst-js-label-upload'),
				  responseMsgElems = document.querySelectorAll('.mc-tst__response-message');
			if ( responseMsgElems.length === 0 ) return;

			image.value    = '';
			rating.value   = '';
			message.value  = '';
			fullname.value = '';
			position.value = '';
			imageLabel.textContent = 'Upload Profile';
			this.resetRatingStarsEvent();
		},

		/**
		 * Set the action button state |disbled|enabled.
		 *
		 * @since 1.0.0
		 */
		setActionState: function( state ) {
			const actionBtnElems = document.querySelectorAll('.mc-tst__action-btn');
			if ( actionBtnElems.length === 0 ) return;

			actionBtnElems.forEach( function( actionBtnElem ) {
				if ( state == 'disable' ) {
					actionBtnElem.setAttribute( 'disabled', '' );
				} else if ( state == 'enable' ) {
					actionBtnElem.removeAttribute('disabled');
				}
			});
		},

		/**
		 * Showing the response message.
		 *
		 * @since 1.0.0
		 * 
		 * @param  {string}  message  The message content.
		 * @param  {string}  type     The type of response message |success|error.
		 */
		showResponseMsg: function( message, type ) {
			if ( ! message || ( type != 'success' && type != 'error' ) ) return;

			const responseMsgElem = document.getElementById(`mc-tst-js-${ type }-message`);
			if ( ! responseMsgElem ) return;

			if ( type === 'success' ) {
				const responseErrorMsgElem = document.getElementById('mc-tst-js-error-message');
				if ( responseErrorMsgElem ) {
					responseErrorMsgElem.setAttribute( 'data-state', 'hidden' );
				}
			}

			responseMsgElem.textContent = message;
			responseMsgElem.setAttribute( 'data-state', 'visible' );
			setTimeout( function(){
				responseMsgElem.setAttribute( 'data-state', 'hidden' );
			}, 10000 );
		},

		/**
		 * Hides all the response message.
		 *
		 * @since 1.0.0
		 */
		hideResponseMsg: function() {
			const responseMsgElems = document.querySelectorAll('.mc-tst__response-message');
			if ( responseMsgElems.length === 0 ) return;

			responseMsgElems.forEach( function( responseMsgElem ) {
				responseMsgElem.setAttribute( 'data-state', 'hidden' );
			});
		}
	};
	mctst.init();
</script>