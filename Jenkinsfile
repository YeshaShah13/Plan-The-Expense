// pipeline {
//     agent any

//     environment {
//         // Define any environment variables here if needed
//         PHP_APP_PORT = "8000"
//     }

//     stages {
//         stage('Checkout Code') {
//             steps {
//                 echo "Checking out code..."
//                 git branch: 'main', url: 'https://github.com/YeshaShah13/Plan-The-Expense.git'
//             }
//         }

//         stage('Stop Existing Containers') {
//             steps {
//                 echo "Stopping any existing containers..."
//                 sh 'docker-compose -f docker-compose.yml down || true'
//             }
//         }

//         stage('Build & Deploy') {
//             steps {
//                 echo "Building and starting containers..."
//                 // Change the port mapping if needed
//                 sh "sed -i 's/:[0-9]*:80/:${PHP_APP_PORT}:80/' docker-compose.yml || true"
//                 sh 'docker-compose -f docker-compose.yml up -d --build'
//             }
//         }

//         stage('Verify Deployment') {
//             steps {
//                 echo "Listing running containers..."
//                 sh 'docker ps'
//             }
//         }
//     }

//     post {
//         success {
//             echo "Deployment successful! PHP app should be running on port ${PHP_APP_PORT}"
//         }
//         failure {
//             echo "Deployment failed. Check the logs above for details."
//         }
//     }
// }
pipeline {
    agent any

    environment {
        PHP_APP_PORT = "8000" // compose should use "${PHP_APP_PORT:-8000}:80"
    }

    stages {
        stage('Docker Preflight') {
            steps {
                echo "Checking Docker and Compose availability..."
                sh 'docker info || (echo "Docker daemon not reachable" && exit 1)'
                sh 'docker compose version || (echo "docker compose v2 not found" && exit 1)'
            }
        }

        stage('Checkout Code') {
            steps {
                echo "Checking out code..."
                git branch: 'main', url: 'https://github.com/YeshaShah13/Plan-The-Expense.git'
            }
        }

        stage('Stop Existing Containers') {
            steps {
                echo "Stopping any existing containers..."
                sh 'docker compose -f docker-compose.yml down || true'
            }
        }

        stage('Build & Deploy') {
            steps {
                echo "Building and starting containers..."
                // Ensure docker-compose.yml maps ports with: "${PHP_APP_PORT:-8000}:80"
                sh 'docker compose -f docker-compose.yml up -d --build'
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