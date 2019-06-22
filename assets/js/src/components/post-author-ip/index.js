/**
 * WordPress dependencies
 */
import { Disabled, PanelBody, TextControl } from '@wordpress/components';
import { compose, ifCondition } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginPostStatusInfo } from '@wordpress/edit-post';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export class PostAuthorIP extends Component {
	render() {
		const {
			meta: {
				'c2c-post-author-ip': PostAuthorIP,
			} = {},
			updateMeta,
		} = this.props;

		return (
			<PluginPostStatusInfo>
			<Disabled
				className="post-author-ip-disabled"
			>
				<TextControl
					label={ __( 'Author IP Address', 'post-author-ip' ) }
					className="post-author-ip"
					value={ PostAuthorIP }
					onChange={ () => {} }
				/>
			</Disabled>
			</PluginPostStatusInfo>
		);
	}
}

export default compose( [
	withSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );

		return {
			meta: getEditedPostAttribute( 'meta' ),
		};
	} ),
	withDispatch( ( dispatch, { meta } ) => {
		const { editPost } = dispatch( 'core/editor' );

		return {
			updateMeta( newMeta ) {
				// Prevent field from getting assigned a new value.
				delete(meta['c2c-post-author-ip']);

				editPost( { meta: meta } );
			},
		};
	} ),
	ifCondition( ( { meta } ) => '' != meta['c2c-post-author-ip'] ),
] )( PostAuthorIP );
