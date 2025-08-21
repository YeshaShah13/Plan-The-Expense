pipeline {
    agent any

    environment {
        // Define any environment variables here if needed
        PHP_APP_PORT = "8000"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out code..."
                git branch: 'main', url: 'https://github.com/YeshaShah13/Plan-The-Expense.git'
            }
        }

        stage('Stop Existing Containers') {
            steps {
                echo "Stopping any existing containers..."
                sh 'docker-compose -f docker-compose.yml down || true'
            }
        }

        stage('Build & Deploy') {
            steps {
                echo "Building and starting containers..."
                // Change the port mapping if needed
                sh "sed -i 's/:[0-9]*:80/:${PHP_APP_PORT}:80/' docker-compose.yml || true"
                sh 'docker-compose -f docker-compose.yml up -d --build'
            }
        }

        stage('Verify Deployment') {
            steps {
                echo "Listing running containers..."
                sh 'docker ps'
            }
        }
    }

    post {
        success {
            echo "Deployment successful! PHP app should be running on port ${PHP_APP_PORT}"
        }
        failure {
            echo "Deployment failed. Check the logs above for details."
        }
    }
}
