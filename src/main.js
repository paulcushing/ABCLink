import React from 'react';
import { Alert, Button, Container, Row, Col, FormGroup, Input, Label } from 'reactstrap';

class Main extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			destinations: ['', ''],
			error: null,
			success: null,
			apiResponse: null
		};
	}

	addFields = () => {
		const destinations = this.state.destinations;
		destinations.push('');
		this.setState({ destinations: destinations });
	};

	destinationChange = (event, key) => {
		const destinations = this.state.destinations;
		const { value } = event.target;
		destinations[key] = value;

		this.setState({ destinations: destinations });
	};

	saveDestinations = () => {
		if (this.state.destinations.includes('')) {
			this.setState({ error: 'It looks like you left one or more destinations blank.' });
		} else if (this.state.destinations.find((a) => a.includes('yoursite.com'))) {
			this.setState({ error: "You left the placeholder in. How'd you manage that?" });
		} else {
			this.setState({ error: null });
			const createEndpoint = 'http://localhost:9000/create';

			fetch(createEndpoint, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(this.state.destinations)
			})
				.then((res) => res.text())
				.then((res) => this.setState({ apiResponse: res, success: res }));

			// axios({
			// 	method: 'post',
			// 	url: `${API_PATH}`,
			// 	headers: { 'content-type': 'application/json' },
			// 	data: { destinations: this.state.destinations }
			// })
			// 	.then(result => {
			// 		console.log(result.data);
			// 		this.setState({
			// 			success:
			// 				"You're all set. Your shortlink is: " +
			// 				result.data.Success +
			// 				" (We've copied it to the clipboard for you.)",
			// 			destinations: ['', '']
			// 		});

			// 		const copyText = document.createElement('textarea');
			// 		document.body.appendChild(copyText);
			// 		copyText.value = result.data.Success;
			// 		copyText.select();
			// 		document.execCommand('copy');
			// 		document.body.removeChild(copyText);
			// 	})
			// 	.catch(error => this.setState({ error: error.message }));
		}
	};

	render() {
		const destinationFields = () => {
			const destFieldList = this.state.destinations.map((fieldValue, key) => {
				const alpha = (key + 10).toString(36);
				const placeholderUrl = 'https://www.yoursite.com/' + alpha;
				return (
					<Input
						type="text"
						name="destination"
						className="destination-field"
						placeholder={placeholderUrl}
						bsSize="lg"
						maxLength={256}
						value={fieldValue}
						autoComplete="off"
						onChange={(event) => this.destinationChange(event, key)}
						key={'destination-' + key}
					/>
				);
			});

			return destFieldList;
		};

		const formErrorAlert = () => {
			return this.state.error ? <Alert color="danger">{this.state.error}</Alert> : null;
		};

		const shortLinkCreated = () => {
			return this.state.success ? <Alert color="success">{this.state.success}</Alert> : null;
		};

		return (
			<Container className="wrapper">
				<Container className="main-container">
					<Row>
						<Col>
							<h1 className="main-title">ABCL.ink - Create A/B Testing Shortlinks</h1>
						</Col>
					</Row>

					<Row>
						<Col>
							<FormGroup>
								<Label for="destination">Destination URLs</Label>
								{destinationFields()}
							</FormGroup>
						</Col>
					</Row>
					<Row>
						<Col>
							<FormGroup>
								<Button onClick={this.addFields}>Add More Destinations</Button>
							</FormGroup>
						</Col>
					</Row>
					<Row>
						<Col>
							<ul>
								<li>
									Input as many landing page links as you have variants to split
									test. Click the button to add additional destinations. (include
									https:// or http://)
								</li>
								<li>
									The resulting link will forward the user to a random one of the
									URLs you've provided.
								</li>
								<li>Click "COPY LINK" to copy the link to your clipboard.</li>
							</ul>
						</Col>
					</Row>
					<Row>
						<Col>
							{formErrorAlert()}
							<Button color="primary" size="lg" block onClick={this.saveDestinations}>
								Create Short Link
							</Button>
						</Col>
					</Row>
					<Row>
						<Col>{shortLinkCreated()}</Col>
					</Row>
				</Container>
			</Container>
		);
	}
}

export default Main;
