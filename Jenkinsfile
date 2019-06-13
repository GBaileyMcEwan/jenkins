pipeline {
	agent any
	stages {
		stage('Deploy') {
			steps {
				echo 'Running deployment'
				archiveArtifacts artifacts: 'src/index.html'
			}
		}
	}
}
