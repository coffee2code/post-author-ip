import { registerPlugin } from '@wordpress/plugins';
import { default as PostAuthorIP } from './components/post-author-ip';

registerPlugin(
	'post-author-ip',
	{
		render: PostAuthorIP,
	}
);
