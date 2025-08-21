pipeline {
    agent any

    // Parameters for dynamic behavior
    parameters {
        string(name: 'PROJECT_NAME', defaultValue: 'php-docker-project', description: 'Name of your project')
        string(name: 'PROJECT_PORT', defaultValue: '8000', description: 'Port for Apache server')
        choice(name: 'ACTION', choices: ['Build & Deploy', 'Skip Build'], description: 'Choose action for this run')
    }

    environment {
        COMPOSE_FILE = "${params.PROJECT_NAME}/docker-compose.yml"
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out project ${params.PROJECT_NAME}"
                checkout scm
            }
        }

        stage('Build and Deploy') {
            when {
                expression { params.ACTION == 'Build & Deploy' }
            }
            steps {
                script {
                    echo "Stopping existing containers (if any)..."
                    sh "docker-compose -f ${env.COMPOSE_FILE} down || true"

                    echo "Starting containers for ${params.PROJECT_NAME} on port ${params.PROJECT_PORT}..."
                    sh """
                    docker-compose -f ${env.COMPOSE_FILE} up -d --build
                    """
                }
            }
        }

        stage('Skip Build') {
            when {
                expression { params.ACTION == 'Skip Build' }
            }
            steps {
                echo "Build & Deploy skipped by user."
            }
        }

        stage('Deployment Status') {
            steps {
                echo "Pipeline finished for ${params.PROJECT_NAME} with action: ${params.ACTION}"
                echo "Access project at: http://localhost:${params.PROJECT_PORT} (if deployed)"
            }
        }
    }

    post {
        always {
            echo "Pipeline run completed."
        }
    }
}
