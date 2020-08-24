import React from 'react';
import Main from './main';
import axios from 'axios';

class Wrapper extends React.Component {
	constructor(props) {
		super(props);

		const shortLink =
			window.location.search !== undefined ? window.location.search.substring(1) : null;

		this.state = {
			shortLink: shortLink
		};
	}

	tryRedirect = () => {
		const API_PATH = '/api/follow.php';
		axios({
			method: 'post',
			url: `${API_PATH}`,
			headers: { 'content-type': 'application/json' },
			data: { shortLink: this.state.shortLink }
		})
			.then((result) => {
				console.log(result.data);
				window.location.href = result.data.Success;
			})
			.catch((error) => this.setState({ error: error.message }));

		return null;
	};

	render() {
		return this.state.shortLink ? this.tryRedirect() : <Main />;
	}
}

export default Wrapper;
