# Authenticated-file-storage-system
Project Overview
This project implements a Color-Based Authentication System as part of a multi-step authentication process. Users must select colors in the correct sequence to proceed to the next step. The system dynamically generates a random color palette and verifies user input against a predefined pattern.

Files in the Project
1. color_verification.html
This is the main frontend file for the color-based authentication system. It includes:

A gradient-themed user interface.

Dynamic generation of color buttons.

Logic to shuffle, display, and verify color patterns.

2. database.php
This PHP script connects to the database for handling backend operations such as storing user authentication details and validating data.

3. verify.php
This PHP file processes user verification requests. It checks if the provided data matches stored records and returns appropriate responses.

Features
Dynamic Color Buttons

Generates a randomized set of colors using JavaScript.

Ensures that every attempt provides a shuffled pattern.

Pattern Verification

Verifies the user's selected color sequence against the predefined pattern.

Provides feedback and resets the interface if the input is incorrect.

User-Friendly Interface

Aesthetic design with gradient background and smooth animations.

Clear and interactive instructions.

Redirect on Success

On successful verification, redirects users to gesture_verification.html for the next authentication step.

How to Run the Project
Setup Backend

Ensure a PHP server is running (e.g., Apache, XAMPP).

Place database.php and verify.php in the server's directory.

Setup Database

Create a database to store user authentication details.

Configure database.php with the correct database credentials.

Run Frontend

Open color_verification.html in a browser.

Follow the on-screen instructions to test the color authentication system.

Customization
Changing the Color Pattern
To modify the expected color pattern, update the correctPattern variable in color_verification.html:

const correctPattern = ["Your", "New", "Pattern"];
Adding More Colors
To add more color options, edit the colors array in the createColorButtons function:

const colors = ["NewColor", "ExistingColors", ...];
Future Enhancements
Database Integration for Patterns

Store and retrieve correct patterns from the database for added flexibility.

Error Logging

Log user attempts to identify frequent errors and improve the user experience.

Multi-Factor Authentication

Combine color verification with other steps like gesture or biometric authentication.

Responsive Design

Optimize for mobile and tablet devices.

Contributing
Contributions are welcome! Feel free to fork the repository, create a new branch, and submit a pull request.

License
This project is licensed under the MIT License. Feel free to use, modify, and distribute the code.
