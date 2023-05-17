// Import required modules
import Template from "./modules/template";

// App extends Template
export default class App extends Template {
    /*
     * Auto called when creating a new instance
     *
     */
    constructor() {
        super();
    }
}

// Create a new instance of App
window.One = new App();
