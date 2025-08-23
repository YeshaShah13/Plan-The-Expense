pipeline {
    agent any

    environment {
        PHP_APP_PORT = "9001"
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

        // 
        stage('Verify Deployment') {
            steps {
                echo "Waiting for services to fully initialize..."
                sh 'sleep 45'  // Increased from 10 to 45 seconds
        
                echo "Testing database connectivity first..."
                sh '''
                    docker compose exec -T db mysqladmin ping -h localhost -u root -proot123 || {
                    echo "Database not ready"
                    docker compose logs db
                    exit 1
                    }
                '''
        
             echo "Testing PHP database connection..."
                sh '''
                    docker compose exec -T php php -r "
                    include 'config.php';
                    if (isset(\$con)) {
                        echo 'PHP can connect to database successfully';
                    } else {
                    echo 'PHP cannot connect to database';
                    exit 1;
                    }
                    "
                '''
        
                echo "Testing application..."
                sh '''
                    for i in {1..10}; do
                    if curl -f -s ${DEPLOY_URL}/index.php > /dev/null; then
                        echo "✅ Application is responding!"
                        break
                    fi
                    if [ $i -eq 10 ]; then
                        echo "❌ Application failed to respond"
                        exit 1
                    fi
                    echo "Attempt $i failed, retrying in 5 seconds..."
                    sleep 5
                    done
                '''
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
            echo "🔍 Testing direct container access:"
            sh 'docker exec plantheexpense-php-1 ls -la /var/www/html/ || true'
            sh 'docker exec plantheexpense-php-1 cat /var/log/apache2/error.log || true'
        }
    }
}