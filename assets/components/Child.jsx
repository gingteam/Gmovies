import React, { Component } from 'react';

/**
 * @extends Component<{name: string}>
 */
export class Child extends Component {
    render() {
        const { name } = this.props;
        return (
            <div>
                { name }
            </div>
        );
    }
}
