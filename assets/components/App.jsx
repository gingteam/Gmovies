import React, { Component } from 'react';
import { Child } from './Child';

export class App extends Component {
    render()
    {
        return (
            <div>
                <h1>Hello, world!</h1>
                <Child name="John" />
            </div>
        );
    }
}
