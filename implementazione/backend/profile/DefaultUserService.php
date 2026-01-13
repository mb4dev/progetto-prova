<?php

class DefaultUserService implements UserService {

	public function __construct(private UserRepository $userRepository) {}

	public function getById(int $id): Response {
		try{
			$user = $this->userRepository->getById($id);
			return new Response(200, true, ["user" => $user]);
		}
		catch(Exception $e){
			if ($e instanceof UserNotFoundException) {
				return new Response(404, false, ["error" => $e->getMessage()]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	public function update(User $user): Response {
		try{
			$userUpdated = $this->userRepository->update($user);
			return new Response(200, true, ["user" => $userUpdated]);
		}
		catch(Exception $e){
			if ($e instanceof UserNotFoundException) {
				return new Response(404, false, ["error" => $e->getMessage()]);
			}
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}
}