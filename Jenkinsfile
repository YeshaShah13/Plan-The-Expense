pipeline {
    agent any

    environment {
        PHP_APP_PORT = "9000"
        DEPLOY_URL = "http://localhost:${PHP_APP_PORT}"
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
                sh 'docker compose -f docker-compose.yml down --remove-orphans || true'
            }
        }

        stage('Build & Deploy') {
            steps {
                echo "Building and starting containers..."
                sh 'docker compose -f docker-compose.yml up -d --build'
            }
        }

        stage('Verify Deployment') {
            steps {
                echo "Listing running containers..."
                sh 'docker ps'
                echo "Testing app availability..."
                sh 'curl -f -I ${DEPLOY_URL}/index.php || echo "App not responding yet"'
            }
        }
    }

    post {
        success {
            echo "🎉 Deployment successful!"
            echo "📱 Your PHP app is now running at: ${DEPLOY_URL}"
            echo "🔗 Direct links:"
            echo "   - Dashboard: ${DEPLOY_URL}/index.php"
            echo "   - Login: ${DEPLOY_URL}/login.php"
            echo "   - Register: ${DEPLOY_URL}/register.php"
            echo "📊 Container status:"
            sh 'docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"'
        }
        failure {
            echo "❌ Deployment failed. Check the logs above for details."
            echo "🔍 Debug info:"
            sh 'docker ps -a || true'
            sh 'docker compose -f docker-compose.yml logs --tail=50 || true'
        }
    }
}